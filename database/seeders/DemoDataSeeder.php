<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Datos de demostracion para T-HUMAN (Organizacion Bless, confeccion de jean).
 * Inserta con DB::table (sin eventos ni auditoria) y en lote.
 * Uso: php artisan db:seed --class=DemoDataSeeder --force
 * Clave de TODOS los usuarios demo: Demo2026*
 */
class DemoDataSeeder extends Seeder
{
    private CarbonImmutable $now;
    private array $ids = [];
    private array $buf = [];
    private string $pwd;
    private array $usedDoc = ['1000000000' => 1];
    private array $usedEmail = ['superadmin@thuman.com' => 1];
    private array $usedPhone = ['3000000000' => 1];
    private int $emailSeq = 0;

    // Catalogos
    private array $C = [];      // nombre => id por catalogo
    private array $cie = [];
    private array $roleIds = [];

    // Organizacion
    private int $enterpriseId;
    private array $entityIds = [];   // [1=>id,2=>id]
    private array $hq = [];          // clave => id
    private array $areaIds = [];
    private array $pos = [];         // clave => [id, def...]
    private array $campaignIds = [];

    // Personas
    private array $P = [];           // id => meta
    private array $emp = [];         // lista empleados (meta)
    private array $cands = [];       // candidatos (meta)
    private array $forms = [];
    private array $vacants = [];

    private const SMMLV = 1750905;
    private const TRANSPORT = 249095;
    private const MH = 'App\\Models\\';

    public function run(): void
    {
        if (DB::table('enterprises')->count() > 0 || DB::table('people')->count() > 1) {
            $this->command?->warn('La base ya contiene datos de demostracion. Se omite.');
            return;
        }

        mt_srand(20261116);
        $this->now = CarbonImmutable::now();
        DB::disableQueryLog();
        $this->pwd = Hash::make('Demo2026*');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::beginTransaction();
        try {
            $this->step('catalogos', fn() => $this->catalogs());
            $this->step('organizacion', fn() => $this->organization());
            $this->step('cargos', fn() => $this->positions());
            $this->step('empleados', fn() => $this->employees());
            $this->step('candidatos', fn() => $this->candidates());
            $this->step('formularios', fn() => $this->makeForms());
            $this->step('vacantes y aplicaciones', fn() => $this->recruitment());
            $this->step('contratos', fn() => $this->contracts());
            $this->step('novedades', fn() => $this->hrEvents());
            $this->step('inducciones y encuestas', fn() => $this->inductionsAndSurveys());
            $this->step('escritura', fn() => $this->flush());
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            throw $e;
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    // ------------------------------------------------------------ utilidades
    private function step(string $n, callable $f): void
    {
        $t = microtime(true);
        $f();
        $this->command?->info(sprintf('[%s] %.1fs', $n, microtime(true) - $t));
    }

    private function nid(string $t): int
    {
        if (!isset($this->ids[$t])) {
            $this->ids[$t] = (int) DB::table($t)->max('id');
        }
        return ++$this->ids[$t];
    }

    private function add(string $t, array $row): void
    {
        $this->buf[$t][] = $row;
    }

    private function flush(): void
    {
        $order = array_keys($this->buf);
        foreach ($order as $t) {
            $rows = $this->buf[$t];
            $keys = [];
            foreach ($rows as $r) {
                foreach ($r as $k => $_) {
                    $keys[$k] = true;
                }
            }
            $keys = array_keys($keys);
            $norm = [];
            foreach ($rows as $r) {
                $o = [];
                foreach ($keys as $k) {
                    $o[$k] = $r[$k] ?? null;
                }
                $norm[] = $o;
            }
            foreach (array_chunk($norm, 300) as $c) {
                DB::table($t)->insert($c);
            }
        }
        $this->buf = [];
    }

    private function j($v): string
    {
        return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function ri(int $a, int $b): int
    {
        return mt_rand($a, $b);
    }

    private function pick(array $a)
    {
        return $a[mt_rand(0, count($a) - 1)];
    }

    private function chance(float $p): bool
    {
        return mt_rand(0, 9999) < $p * 10000;
    }

    private function weighted(array $w)
    {
        $t = array_sum($w);
        $r = mt_rand(1, (int) $t);
        foreach ($w as $k => $v) {
            $r -= $v;
            if ($r <= 0) {
                return $k;
            }
        }
        return array_key_first($w);
    }

    private function ts(CarbonImmutable $d): string
    {
        return $d->format('Y-m-d H:i:s');
    }

    private function dt(CarbonImmutable $d): string
    {
        return $d->format('Y-m-d');
    }

    private function ago(int $min, int $max): CarbonImmutable
    {
        return $this->now->subDays($this->ri($min, $max))->setTime($this->ri(7, 18), $this->ri(0, 59), $this->ri(0, 59));
    }

    private function biz(CarbonImmutable $d): CarbonImmutable
    {
        while ($d->isWeekend()) {
            $d = $d->subDay();
        }
        return $d->setTime($this->ri(8, 16), $this->pick([0, 15, 30, 45]), 0);
    }

    private function min(CarbonImmutable $a, CarbonImmutable $b): CarbonImmutable
    {
        return $a < $b ? $a : $b;
    }

    private function sub(int $itemId, string $name): int
    {
        $r = DB::table('subitems')->where('item_id', $itemId)->where('name', $name)->value('id');
        if (!$r) {
            throw new \RuntimeException("Subitem no encontrado: $itemId/$name");
        }
        return (int) $r;
    }

    private function cat(int $item, string $key, array $rows, ?callable $each = null): void
    {
        $out = [];
        foreach ($rows as $r) {
            $id = $this->nid('subitems');
            $out[] = [
                'id' => $id, 'item_id' => $item, 'name' => $r[0], 'description' => $r[1] ?? ($r[0] . '.'),
                'settings' => $this->j($r[2] ?? new \stdClass()),
                'created_at' => $this->ts($this->now->subDays(400)), 'updated_at' => $this->ts($this->now->subDays(400)),
            ];
            $this->C[$key][$r[0]] = $id;
            if ($each) {
                $each($id, $r);
            }
        }
        DB::table('subitems')->insert($out);
    }

    private function link(int $subitemId, string $modelType, int $modelId): void
    {
        $this->add('model_has_subitems', ['subitem_id' => $subitemId, 'model_type' => $modelType, 'model_id' => $modelId]);
    }

    private function phone(): string
    {
        do {
            $n = '3' . $this->pick(['00', '01', '04', '05', '10', '12', '14', '15', '16', '17', '20', '21', '50', '51']) . str_pad((string) $this->ri(0, 9999999), 7, '0', STR_PAD_LEFT);
            $n = substr($n, 0, 10);
        } while (isset($this->usedPhone[$n]));
        $this->usedPhone[$n] = 1;
        return $n;
    }

    // ------------------------------------------------------------ catalogos
    private function catalogs(): void
    {
        $country = self::MH . 'Country';

        $this->cat(24, 'arl', [['Positiva Compañía de Seguros'], ['ARL SURA'], ['Colmena Seguros ARL'], ['Seguros Bolívar ARL'], ['Liberty Seguros ARL'], ['AXA Colpatria ARL']], fn($id) => $this->link($id, $country, 48));

        $eps = ['Nueva EPS', 'EPS Sanitas', 'EPS SURA', 'Salud Total EPS', 'Coosalud EPS', 'Famisanar EPS', 'Compensar EPS', 'Mutual Ser EPS', 'Savia Salud EPS', 'Asmet Salud EPS'];
        $ips = ['IPS Salud Ocupacional del Oriente (Cúcuta)', 'Centro Médico Ocupacional Norte (Cúcuta)', 'Medicina Laboral Antioquia IPS (Medellín)', 'IPS Vida Laboral Medellín'];
        $this->cat(26, 'health', array_merge(array_map(fn($n) => [$n], $eps), array_map(fn($n) => [$n], $ips)), function ($id, $r) use ($country, $eps) {
            $this->link($id, $country, 48);
            $this->link(in_array($r[0], $eps) ? 340 : 341, self::MH . 'HealthEntity', $id);
        });

        $this->cat(27, 'ccf', [['Comfaoriente'], ['Comfenalco Antioquia'], ['Comfama'], ['Cafam'], ['Colsubsidio']], fn($id) => $this->link($id, $country, 48));
        $this->cat(28, 'pension', [['Colpensiones'], ['Porvenir'], ['Protección'], ['Colfondos'], ['Skandia']], fn($id) => $this->link($id, $country, 48));
        $this->cat(31, 'fiscal', [['RUT - Registro Único Tributario', 'Documento de identificación tributaria ante la DIAN.'], ['Certificado de ingresos y retenciones', 'Soporte de ingresos y retenciones del año gravable anterior.']], fn($id) => $this->link($id, $country, 48));
        $this->cat(32, 'epi', [['Riesgo biomecánico (desórdenes músculo-esqueléticos)'], ['Riesgo psicosocial'], ['Conservación auditiva'], ['Riesgo respiratorio por material particulado (pelusa de algodón)'], ['Riesgo cardiovascular'], ['Riesgo visual']]);
        $this->cat(35, 'provider', [['Poligrafía Integral del Oriente S.A.S.'], ['Servicios de Confiabilidad Antioquia S.A.S.'], ['Polígrafos y Seguridad Andina Ltda.']], fn($id) => $this->link(353, self::MH . 'ServiceProvider', $id));
        $this->cat(37, 'policyType', [['Póliza de cumplimiento'], ['Póliza de responsabilidad civil extracontractual'], ['Póliza de salarios y prestaciones sociales'], ['Póliza de calidad del servicio']]);
        $this->cat(38, 'addendumType', [['Prórroga del contrato'], ['Cambio de cargo'], ['Ajuste salarial'], ['Traslado de sede'], ['Cambio de tipo de contrato']]);
        $this->cat(19, 'benefit', [['Auxilio de transporte'], ['Bono por productividad'], ['Dotación de uniforme y calzado'], ['Seguro de vida grupo'], ['Servicio de alimentación en planta'], ['Prima extralegal de mitad de año'], ['Día libre por cumpleaños'], ['Convenio de salud prepagada']]);
        $this->cat(40, 'absenceType', [['Cita médica'], ['Cita odontológica'], ['Calamidad doméstica'], ['Diligencia personal'], ['Permiso de estudio'], ['Trámite notarial o judicial']]);
        $this->cat(41, 'vacationType', [['Vacaciones disfrutadas'], ['Vacaciones compensadas en dinero'], ['Vacaciones colectivas (cierre de fin de año)']]);
        $this->cat(42, 'severanceType', [['Cesantías parciales para vivienda'], ['Cesantías parciales para educación'], ['Cesantías definitivas por retiro']]);
        $this->cat(43, 'disabilityType', [['Enfermedad general'], ['Accidente de trabajo'], ['Enfermedad laboral'], ['Accidente de tránsito']]);
        $this->cat(44, 'licenseType', [['Licencia de maternidad'], ['Licencia de paternidad'], ['Licencia por luto'], ['Licencia no remunerada'], ['Licencia por matrimonio']]);
        $this->cat(46, 'resignationType', [['Renuncia voluntaria'], ['Renuncia por mutuo acuerdo'], ['Renuncia por oferta laboral']]);
        $this->cat(47, 'terminationType', [['Terminación por vencimiento del término'], ['Terminación unilateral sin justa causa'], ['Terminación con justa causa'], ['Terminación por mutuo acuerdo'], ['Terminación de obra o labor']]);

        $soft = [
            ['Trabajo en equipo', 21], ['Comunicación asertiva', 21], ['Orientación al servicio', 21], ['Responsabilidad y compromiso', 21], ['Liderazgo', 21], ['Atención al detalle', 21],
            ['Adaptabilidad', 21], ['Manejo del tiempo', 21], ['Negociación', 21], ['Resolución de problemas', 21], ['Pensamiento analítico', 21], ['Orientación a resultados', 21],
            ['Costura industrial en máquina plana', 22], ['Manejo de fileteadora de 3 y 5 hilos', 22], ['Lectura de fichas técnicas', 22], ['Corte con máquina de cinta y cuchilla vertical', 22], ['Patronaje y escalado industrial', 22],
            ['Manejo de Excel', 22], ['Facturación y manejo de caja', 22], ['Manejo de inventarios y bodega', 22], ['Control de calidad textil', 22], ['Diseño de moda digital', 22], ['Contabilidad NIIF', 22],
            ['Nómina y seguridad social', 22], ['Atención al cliente en punto de venta', 22], ['Manejo de montacargas', 22], ['Mantenimiento de maquinaria de confección', 22], ['Soporte técnico y redes', 22],
            ['Ventas consultivas', 22], ['Selección de personal por competencias', 22], ['Manejo de ERP contable', 22], ['Acabados y terminación de prendas', 22],
        ];
        $this->cat(29, 'skill', array_map(fn($s) => [$s[0], $s[0] . '.'], $soft), function ($id, $r) use ($soft) {
            foreach ($soft as $s) {
                if ($s[0] === $r[0]) {
                    $this->link($s[1], self::MH . 'Skill', $id);
                }
            }
        });
        $this->skillType = [];
        foreach ($soft as $s) {
            $this->skillType[$s[0]] = $s[1];
        }

        // Tipos de contrato -> plantillas
        foreach ([1, 2, 4, 5] as $ct) {
            $this->link($ct, self::MH . 'FileSubtype', 112);
        }
        $this->link(3, self::MH . 'FileSubtype', 113);
        foreach ([342, 343, 344, 345, 346, 347, 348, 349] as $et) {
            $this->link($et, $country, 48);
        }

        // CIE-10 frecuentes en incapacidades
        $names = [
            'Rinofaringitis Aguda [Resfriado Comun]', 'Lumbago No Especificado', 'Diarrea Y Gastroenteritis De Presunto Origen Infeccioso', 'Sindrome Del Tunel Carpiano',
            'Esguinces Y Torceduras Del Tobillo', 'Gastritis; No Especificada', 'Migrana; No Especificada', 'Bronquitis Aguda; No Especificada', 'Infeccion De Vias Urinarias; Sitio No Especificado',
            'Faringitis Aguda; No Especificada', 'Lumbago Con Ciatica',
        ];
        foreach ($names as $n) {
            $id = DB::table('subitems')->where('item_id', 51)->where('name', $n)->value('id');
            if ($id) {
                $this->cie[$n] = (int) $id;
            }
        }
        foreach (['Dengue Clasico%', 'Hipertension Esencial%', 'Fractura De Falange%', 'Apendicitis Aguda%', 'Trastorno De Ansiedad%', 'Cefalea%'] as $l) {
            $r = DB::table('subitems')->where('item_id', 51)->where('name', 'like', $l)->first();
            if ($r) {
                $this->cie[$r->name] = (int) $r->id;
            }
        }
        if (!$this->cie) {
            throw new \RuntimeException('No se encontraron codigos CIE-10.');
        }

        $this->roleIds = DB::table('roles')->pluck('id', 'name')->all();
        $this->C['lang'] = ['es' => 188, 'en' => 186, 'pt' => 279, 'fr' => 196];
    }

    private array $skillType = [];

    // ------------------------------------------------------------ organizacion
    private function organization(): void
    {
        $now = $this->now;
        $this->enterpriseId = $this->nid('enterprises');
        $this->add('enterprises', [
            'id' => $this->enterpriseId, 'name' => 'Organización Bless',
            'description' => 'Grupo empresarial colombiano dedicado al diseño, confección y comercialización de jean de alta calidad, con operación en Cúcuta y Medellín.',
            'created_at' => $this->ts($now->subDays(900)), 'updated_at' => $this->ts($now->subDays(30)),
        ]);

        // Representantes legales (empleados de cargos gerenciales)
        $rep1 = $this->person(['sex' => 'M', 'birth' => [1968, 1976], 'city' => 20772, 'employee' => true, 'first' => 'Hernán', 'last1' => 'Villamizar', 'last2' => 'Carrascal', 'created' => $this->now->subMonths(86)]);
        $rep2 = $this->person(['sex' => 'F', 'birth' => [1975, 1983], 'city' => 21074, 'employee' => true, 'first' => 'Luz', 'second' => 'Marina', 'last1' => 'Restrepo', 'last2' => 'Gaviria', 'created' => $this->now->subMonths(32)]);
        $this->rep = [1 => $rep1, 2 => $rep2];

        $e1 = $this->nid('entities');
        $e2 = $this->nid('entities');
        $this->entityIds = [1 => $e1, 2 => $e2];
        $vac = fn() => [
            ['period' => 2024, 'legal_days' => 15, 'days' => [1, 2, 3, 4, 5]],
            ['period' => 2025, 'legal_days' => 15, 'days' => [1, 2, 3, 4, 5]],
            ['period' => 2026, 'legal_days' => 15, 'days' => [1, 2, 3, 4, 5, 6]],
        ];
        $this->add('entities', [
            'id' => $e1, 'enterprise_id' => $this->enterpriseId, 'entity_type_id' => 342, 'name' => 'Bless Jeans S.A.S.', 'country_id' => 48, 'document_type_id' => 18,
            'document_number' => '901458732-4', 'person_id' => $rep1['id'],
            'about_us' => '<p>Somos Bless, una empresa nortesantandereana con más de 15 años diseñando y confeccionando jean para hombre y mujer. Combinamos el oficio de nuestros maestros confeccionistas con tecnología de corte y lavandería para entregar prendas duraderas con el sello de Cúcuta.</p>',
            'mission' => '<p>Diseñar, confeccionar y comercializar prendas de jean que superen las expectativas de nuestros clientes, generando oportunidades de empleo digno en la región.</p>',
            'vision' => '<p>Para 2030 ser la marca de jean colombiana de mayor recordación en el oriente del país, reconocida por su calidad, innovación y por ser un excelente lugar para trabajar.</p>',
            'settings' => $this->j(['vacations' => $vac()]), 'created_at' => $this->ts($now->subDays(880)), 'updated_at' => $this->ts($now->subDays(20)),
        ]);
        $this->add('entities', [
            'id' => $e2, 'enterprise_id' => $this->enterpriseId, 'entity_type_id' => 342, 'name' => 'Bless Medellín S.A.S.', 'country_id' => 48, 'document_type_id' => 18,
            'document_number' => '901633218-7', 'person_id' => $rep2['id'],
            'about_us' => '<p>Bless Medellín es la sede de comercialización y producción satélite del grupo en Antioquia, desde donde atendemos a nuestros aliados comerciales del centro y occidente del país.</p>',
            'mission' => '<p>Acercar la marca Bless a los clientes de Antioquia con un servicio ágil, cercano y de calidad.</p>',
            'vision' => '<p>Consolidar a Medellín como el segundo polo de operación de Bless, con talleres propios y un showroom de referencia.</p>',
            'settings' => $this->j(['vacations' => $vac()]), 'created_at' => $this->ts($now->subDays(600)), 'updated_at' => $this->ts($now->subDays(20)),
        ]);

        $hqs = [
            'main' => [$e1, 'Sede Principal Cúcuta', 20772, 'Avenida 5 # 12-50, Centro'],
            'plant' => [$e1, 'Planta de Producción Cúcuta', 20772, 'Anillo Vial Oriental Km 3, Zona Industrial'],
            'med' => [$e2, 'Sede Medellín - Showroom y Comercial', 21074, 'Calle 10 # 43D-30, El Poblado'],
            'taller' => [$e2, 'Taller Satélite Medellín - Guayabal', 21074, 'Carrera 50 # 10 Sur-85, Guayabal'],
        ];
        foreach ($hqs as $k => $h) {
            $id = $this->nid('headquarters');
            $this->hq[$k] = $id;
            $this->add('headquarters', ['id' => $id, 'entity_id' => $h[0], 'name' => $h[1], 'city_id' => $h[2], 'address' => $h[3], 'created_at' => $this->ts($now->subDays(500)), 'updated_at' => $this->ts($now->subDays(60))]);
        }

        // Aliados y campañas
        $allies = [
            ['Distribuidora Andina de Moda', 'Cadena de almacenes de moda con presencia en el eje cafetero y Antioquia.', ['Jean Hombre Temporada Diciembre', 'Línea Mujer Talla Real', 'Outlet Fin de Temporada']],
            ['Grupo Retail Oriente', 'Operador de tiendas por departamento en Norte de Santander, Santander y Arauca.', ['Regreso a Clases Juvenil', 'Jean Premium Día del Padre']],
            ['Almacenes Vestirama', 'Red de almacenes de ropa familiar con 40 puntos de venta en el país.', ['Colección Básicos Hombre', 'Colección Denim Mujer', 'Vestirama Kids']],
            ['Moda Express Colombia', 'Comercializadora multicanal con venta en línea y tiendas físicas.', ['Black Friday Denim', 'Campaña Madres Denim']],
            ['Comercializadora Frontera', 'Distribuidor mayorista de confecciones en la zona de frontera.', ['Pedido Mayorista Temporada Alta', 'Línea Trabajo y Dotación']],
        ];
        foreach ($allies as $a) {
            $aid = $this->nid('allies');
            $this->add('allies', [
                'id' => $aid, 'entity_id' => $e1, 'country_id' => 48, 'name' => $a[0], 'description' => $a[1],
                'settings' => $this->j(['works' => [['days' => [1, 2, 3, 4, 5]], ['days' => [6]]]]),
                'created_at' => $this->ts($now->subDays($this->ri(200, 700))), 'updated_at' => $this->ts($now->subDays($this->ri(1, 100))),
            ]);
            foreach ($a[2] as $c) {
                $cid = $this->nid('campaigns');
                $this->campaignIds[] = $cid;
                $this->add('campaigns', ['id' => $cid, 'ally_id' => $aid, 'name' => $c, 'description' => 'Campaña de producción y comercialización: ' . mb_strtolower($c) . ' para ' . $a[0] . '.', 'created_at' => $this->ts($now->subDays($this->ri(60, 400))), 'updated_at' => $this->ts($now->subDays($this->ri(1, 50)))]);
            }
        }

        // Pólizas de riesgo no aplican aqui; areas
        $areas = [
            'Gerencia General' => 'Dirección estratégica de la organización.',
            'Talento Humano' => 'Selección, contratación, nómina, bienestar y desarrollo del personal.',
            'Producción' => 'Confección de prendas de jean en planta: máquina plana, fileteado, terminación y lavandería.',
            'Corte y Diseño' => 'Diseño de producto, patronaje, escalado, tendido y corte de tela.',
            'Calidad' => 'Aseguramiento y control de calidad de la prenda en proceso y terminada.',
            'Logística y Bodega' => 'Almacenamiento, alistamiento y despacho de producto terminado e insumos.',
            'Comercial y Ventas' => 'Ventas a aliados comerciales, tiendas propias y servicio al cliente.',
            'Administración y Finanzas' => 'Contabilidad, cartera, tesorería y apoyo administrativo.',
            'Tecnología' => 'Soporte técnico, redes y sistemas de información.',
            'Mantenimiento' => 'Mantenimiento preventivo y correctivo de maquinaria de confección.',
        ];
        foreach ($areas as $n => $d) {
            $id = $this->nid('areas');
            $this->areaIds[$n] = $id;
            $this->add('areas', ['id' => $id, 'name' => $n, 'description' => $d, 'created_at' => $this->ts($now->subDays(800)), 'updated_at' => $this->ts($now->subDays(100))]);
        }

        // Aseguradoras: nada. Politicas: se generan con contratos.
    }

    private array $rep = [];

    // ------------------------------------------------------------ cargos
    private function positions(): void
    {
        $now = $this->now;
        $S = self::SMMLV;
        // clave => [nombre, area, categoria, track, salario, n, poligrafo, video, nivel, skills, acad, exp, resp]
        $defs = [
            'gg' => ['Gerente General', 'Gerencia General', 63, 'admin', 14500000, 0, false, false, 'ger', ['Liderazgo', 'Orientación a resultados', 'Negociación', 'Pensamiento analítico'], ['Profesional', 'Administración de Empresas o Ingeniería Industrial'], [['Gerencia de empresas del sector textil o confección', 96]], ['Dirección estratégica|Define y supervisa la estrategia de la organización.', 'Representación legal|Representa legalmente a la sociedad ante terceros y entidades.', 'Gestión de resultados|Responde por los resultados financieros y operativos.']],
            'gth' => ['Gerente de Talento Humano', 'Talento Humano', 63, 'admin', 8200000, 1, false, false, 'hrl', ['Liderazgo', 'Comunicación asertiva', 'Nómina y seguridad social', 'Selección de personal por competencias'], ['Profesional', 'Psicología, Administración o Ingeniería Industrial'], [['Jefatura de talento humano', 60]], ['Gestión del talento|Planea y dirige selección, contratación, bienestar y formación.', 'Cumplimiento laboral|Garantiza el cumplimiento de la normativa laboral y de seguridad social.', 'Clima organizacional|Lidera planes de clima y retención.']],
            'asel' => ['Analista de Selección y Contratación', 'Talento Humano', 63, 'admin', 3600000, 2, false, false, 'hr', ['Selección de personal por competencias', 'Comunicación asertiva', 'Manejo de Excel', 'Atención al detalle'], ['Profesional', 'Psicología o Administración'], [['Selección de personal', 24]], ['Reclutamiento|Publica vacantes, preselecciona y programa entrevistas.', 'Contratación|Elabora contratos, otrosí y gestiona afiliaciones.', 'Reportes|Reporta indicadores del proceso de selección.']],
            'psi' => ['Psicólogo(a) Organizacional', 'Talento Humano', 63, 'admin', 4200000, 1, false, false, 'hr', ['Selección de personal por competencias', 'Comunicación asertiva', 'Pensamiento analítico', 'Adaptabilidad'], ['Profesional', 'Psicología'], [['Psicología organizacional o selección', 24]], ['Pruebas psicotécnicas|Aplica y califica entrevistas y pruebas psicotécnicas.', 'Bienestar|Diseña programas de bienestar y clima.', 'Riesgo psicosocial|Lidera la batería de riesgo psicosocial.']],
            'anom' => ['Auxiliar de Nómina y Bienestar', 'Talento Humano', 63, 'admin', 2300000, 2, false, false, 'hr', ['Nómina y seguridad social', 'Manejo de Excel', 'Atención al detalle', 'Responsabilidad y compromiso'], ['Tecnólogo', 'Gestión del Talento Humano o Contabilidad'], [['Auxiliar de nómina', 12]], ['Liquidación de nómina|Liquida nómina quincenal y mensual.', 'Novedades|Registra incapacidades, licencias y vacaciones.', 'Bienestar|Apoya actividades de bienestar.']],
            'jprod' => ['Jefe de Producción', 'Producción', 63, 'ops', 6800000, 1, false, false, 'lead', ['Liderazgo', 'Lectura de fichas técnicas', 'Orientación a resultados', 'Resolución de problemas'], ['Profesional', 'Ingeniería Industrial o de Producción'], [['Jefatura de planta de confección', 60]], ['Planeación de producción|Programa módulos y metas diarias de producción.', 'Eficiencia|Controla eficiencia, desperdicio y cumplimiento.', 'Personal de planta|Lidera supervisores y operarios.']],
            'sup' => ['Supervisor de Planta', 'Producción', 63, 'ops', 3900000, 2, false, false, 'lead', ['Liderazgo', 'Lectura de fichas técnicas', 'Costura industrial en máquina plana', 'Trabajo en equipo'], ['Tecnólogo', 'Producción industrial o confección'], [['Supervisión de módulos de confección', 36]], ['Control de módulos|Supervisa el flujo de prendas por módulo.', 'Capacitación|Entrena operarios nuevos en puesto.', 'Reporte diario|Reporta avance y novedades al jefe de producción.']],
            'plana' => ['Operario de Máquina Plana', 'Producción', 62, 'ops', $S, 11, false, false, 'op', ['Costura industrial en máquina plana', 'Lectura de fichas técnicas', 'Atención al detalle', 'Trabajo en equipo'], ['Técnico', 'Confección industrial de ropa exterior'], [['Operario de confección en máquina plana', 12]], ['Costura de prendas|Ejecuta operaciones de costura en jean según ficha técnica.', 'Calidad en el puesto|Autocontrola la calidad de su operación.', 'Orden y aseo|Mantiene limpio su puesto de trabajo.']],
            'overlock' => ['Operario de Fileteadora (Overlock)', 'Producción', 62, 'ops', (int) ($S * 1.05), 4, false, false, 'op', ['Manejo de fileteadora de 3 y 5 hilos', 'Atención al detalle', 'Trabajo en equipo'], ['Técnico', 'Confección industrial'], [['Operario de fileteadora', 12]], ['Fileteado|Realiza operaciones de fileteado y recubrimiento.', 'Calidad|Cumple estándares de puntada y tensión.']],
            'term' => ['Operario de Terminación y Acabados', 'Producción', 62, 'ops', $S, 4, false, false, 'op', ['Acabados y terminación de prendas', 'Atención al detalle', 'Responsabilidad y compromiso'], ['Media', 'Bachiller académico'], [['Terminación de prendas', 6]], ['Acabados|Corta hilos, ojala, pega botones y remaches.', 'Revisión final|Revisa y empaca prendas terminadas.']],
            'lav' => ['Auxiliar de Lavandería y Desmanchado', 'Producción', 62, 'ops', $S, 2, false, false, 'op', ['Acabados y terminación de prendas', 'Responsabilidad y compromiso', 'Trabajo en equipo'], ['Media', 'Bachiller académico'], [['Auxiliar de lavandería industrial', 6]], ['Procesos de lavado|Opera máquinas de lavado y secado según receta.', 'Seguridad química|Aplica normas de manejo de químicos.']],
            'dis' => ['Diseñador de Producto', 'Corte y Diseño', 63, 'admin', 4800000, 1, false, false, 'lead', ['Diseño de moda digital', 'Pensamiento analítico', 'Atención al detalle', 'Comunicación asertiva'], ['Profesional', 'Diseño de Modas o Diseño Industrial'], [['Diseño de producto en denim', 36]], ['Colecciones|Diseña las colecciones de jean por temporada.', 'Fichas técnicas|Elabora fichas técnicas y especificaciones.', 'Tendencias|Investiga tendencias de moda y lavados.']],
            'pat' => ['Patronista y Escalador', 'Corte y Diseño', 63, 'ops', 3800000, 2, false, false, 'op', ['Patronaje y escalado industrial', 'Atención al detalle', 'Lectura de fichas técnicas'], ['Tecnólogo', 'Patronaje y diseño de modas'], [['Patronaje industrial', 24]], ['Patrones|Traza y digitaliza patrones de jean.', 'Escalado|Escala patrones por tallas.']],
            'cort' => ['Cortador de Tela', 'Corte y Diseño', 62, 'ops', 2100000, 4, true, false, 'op', ['Corte con máquina de cinta y cuchilla vertical', 'Atención al detalle', 'Responsabilidad y compromiso'], ['Media', 'Bachiller académico'], [['Corte industrial de tela', 18]], ['Corte|Corta tendidos con cuchilla vertical y cinta.', 'Optimización|Optimiza el consumo de tela.']],
            'tend' => ['Tendedor de Tela', 'Corte y Diseño', 62, 'ops', $S, 2, false, false, 'op', ['Atención al detalle', 'Trabajo en equipo', 'Responsabilidad y compromiso'], ['Media', 'Bachiller académico'], [['Tendido de tela', 6]], ['Tendido|Tiende telas por capas según orden de corte.', 'Control de defectos|Marca defectos de tela.']],
            'jcal' => ['Jefe de Calidad', 'Calidad', 63, 'quality', 5200000, 1, false, false, 'lead', ['Control de calidad textil', 'Liderazgo', 'Resolución de problemas', 'Pensamiento analítico'], ['Profesional', 'Ingeniería Textil, Industrial o afines'], [['Jefatura de calidad en confección', 48]], ['Sistema de calidad|Define estándares y planes de inspección.', 'Reproceso|Analiza causas de reproceso y devoluciones.', 'Auditorías|Realiza auditorías internas y a talleres.']],
            'insp' => ['Inspector de Calidad', 'Calidad', 62, 'quality', 1950000, 3, false, false, 'op', ['Control de calidad textil', 'Atención al detalle', 'Lectura de fichas técnicas'], ['Técnico', 'Control de calidad textil o confección'], [['Inspección de calidad en confección', 12]], ['Inspección|Inspecciona prendas en proceso y terminadas.', 'Registro|Registra defectos y reprocesos.']],
            'clog' => ['Coordinador de Logística', 'Logística y Bodega', 63, 'logistics', 4300000, 1, false, false, 'lead', ['Manejo de inventarios y bodega', 'Liderazgo', 'Manejo de Excel', 'Manejo de ERP contable'], ['Profesional', 'Logística o Ingeniería Industrial'], [['Coordinación de bodega y despachos', 36]], ['Inventarios|Controla inventario de insumos y producto terminado.', 'Despachos|Programa y verifica despachos a aliados.', 'Equipo|Coordina auxiliares y operadores.']],
            'abod' => ['Auxiliar de Bodega', 'Logística y Bodega', 62, 'logistics', $S, 3, true, false, 'op', ['Manejo de inventarios y bodega', 'Manejo de montacargas', 'Responsabilidad y compromiso'], ['Media', 'Bachiller académico'], [['Auxiliar de bodega', 12]], ['Almacenamiento|Recibe, ubica y custodia mercancía.', 'Alistamiento|Alista pedidos según orden de despacho.']],
            'desp' => ['Operador de Despachos', 'Logística y Bodega', 62, 'logistics', 1900000, 2, true, false, 'op', ['Manejo de inventarios y bodega', 'Manejo de Excel', 'Atención al detalle'], ['Media', 'Bachiller académico'], [['Operario de despachos', 12]], ['Despachos|Verifica, empaca y despacha pedidos.', 'Documentos|Diligencia remisiones y guías.']],
            'gcom' => ['Gerente Comercial', 'Comercial y Ventas', 63, 'commercial', 9000000, 1, false, false, 'lead', ['Negociación', 'Liderazgo', 'Ventas consultivas', 'Orientación a resultados'], ['Profesional', 'Administración, Mercadeo o Negocios Internacionales'], [['Gerencia comercial en confección o retail', 72]], ['Estrategia comercial|Define metas y estrategia de ventas.', 'Aliados|Gestiona la relación con aliados comerciales.', 'Presupuesto|Controla el presupuesto de ventas.']],
            'ejec' => ['Ejecutivo Comercial', 'Comercial y Ventas', 63, 'commercial', 3500000, 2, false, true, 'op', ['Ventas consultivas', 'Negociación', 'Comunicación asertiva', 'Manejo de Excel'], ['Profesional', 'Mercadeo, Administración o afines'], [['Ventas B2B en moda o confección', 24]], ['Cartera de aliados|Gestiona y desarrolla cartera de clientes.', 'Pedidos|Toma y hace seguimiento a pedidos.']],
            'aseso' => ['Asesor de Tienda', 'Comercial y Ventas', 62, 'commercial', 1900000, 5, false, true, 'op', ['Atención al cliente en punto de venta', 'Facturación y manejo de caja', 'Orientación al servicio', 'Comunicación asertiva'], ['Media', 'Bachiller académico'], [['Asesoría de ventas en punto de venta', 12]], ['Venta asesorada|Asesora al cliente en la compra de jean.', 'Caja|Factura y cuadra caja diaria.', 'Exhibición|Mantiene el visual merchandising.']],
            'asc' => ['Asistente de Servicio al Cliente', 'Comercial y Ventas', 63, 'commercial', 2200000, 2, false, true, 'op', ['Orientación al servicio', 'Comunicación asertiva', 'Manejo de Excel', 'Resolución de problemas'], ['Tecnólogo', 'Administración o Mercadeo'], [['Servicio al cliente', 12]], ['PQRS|Atiende peticiones, quejas y reclamos.', 'Posventa|Gestiona cambios y garantías.']],
            'cont' => ['Contador Público', 'Administración y Finanzas', 63, 'admin', 5500000, 1, false, false, 'lead', ['Contabilidad NIIF', 'Manejo de ERP contable', 'Pensamiento analítico', 'Atención al detalle'], ['Profesional', 'Contaduría Pública'], [['Contador en empresa de manufactura', 48]], ['Estados financieros|Prepara estados financieros y declaraciones.', 'Impuestos|Cumple obligaciones tributarias.', 'Control|Supervisa auxiliares contables.']],
            'auxc' => ['Auxiliar Contable', 'Administración y Finanzas', 63, 'admin', 2400000, 2, false, false, 'op', ['Contabilidad NIIF', 'Manejo de Excel', 'Manejo de ERP contable'], ['Tecnólogo', 'Contabilidad y finanzas'], [['Auxiliar contable', 12]], ['Causación|Causa facturas y comprobantes.', 'Conciliaciones|Elabora conciliaciones bancarias.']],
            'cart' => ['Analista de Cartera', 'Administración y Finanzas', 63, 'admin', 3000000, 1, false, false, 'op', ['Manejo de Excel', 'Negociación', 'Comunicación asertiva', 'Manejo de ERP contable'], ['Profesional', 'Contaduría, Administración o Finanzas'], [['Gestión de cartera', 24]], ['Cobro|Gestiona el cobro de cartera a aliados.', 'Conciliación|Concilia pagos y notas crédito.']],
            'sis' => ['Analista de Sistemas', 'Tecnología', 63, 'tech', 4600000, 1, false, false, 'op', ['Soporte técnico y redes', 'Resolución de problemas', 'Manejo de ERP contable', 'Pensamiento analítico'], ['Profesional', 'Ingeniería de Sistemas'], [['Soporte y administración de sistemas', 24]], ['Soporte|Atiende incidencias de hardware y software.', 'Infraestructura|Administra redes y copias de seguridad.']],
            'mec' => ['Mecánico de Máquinas de Coser', 'Mantenimiento', 62, 'ops', 2500000, 2, false, false, 'op', ['Mantenimiento de maquinaria de confección', 'Resolución de problemas', 'Responsabilidad y compromiso'], ['Técnico', 'Mecánica industrial o mantenimiento de maquinaria de confección'], [['Mecánico de máquinas industriales de coser', 24]], ['Mantenimiento preventivo|Ejecuta el plan de mantenimiento de máquinas.', 'Correctivo|Repara fallas en máquinas de planta.']],
            'cmed' => ['Coordinador de Sede Medellín', 'Administración y Finanzas', 63, 'admin', 5800000, 0, false, false, 'lead', ['Liderazgo', 'Orientación a resultados', 'Negociación', 'Resolución de problemas'], ['Profesional', 'Administración de Empresas'], [['Coordinación de sede comercial o taller', 48]], ['Operación de sede|Coordina la operación diaria de la sede Medellín.', 'Aliados locales|Atiende aliados comerciales de Antioquia.']],
            'auxm' => ['Auxiliar Administrativo Sede Medellín', 'Administración y Finanzas', 63, 'admin', 2100000, 1, false, false, 'op', ['Manejo de Excel', 'Comunicación asertiva', 'Atención al detalle'], ['Tecnólogo', 'Gestión administrativa'], [['Auxiliar administrativo', 12]], ['Apoyo administrativo|Apoya archivo, compras menores y correspondencia.', 'Atención|Atiende visitantes y llamadas.']],
        ];
        // Gerente general y coordinador Medellin: 1 empleado cada uno (los representantes)
        $defs['gg'][5] = 1;
        $defs['cmed'][5] = 1;

        foreach ($defs as $k => $d) {
            $id = $this->nid('positions');
            $this->pos[$k] = ['id' => $id, 'name' => $d[0], 'area' => $d[1], 'cat' => $d[2], 'track' => $d[3], 'sal' => $d[4], 'n' => $d[5], 'poly' => $d[6], 'video' => $d[7], 'lvl' => $d[8]];
            $this->add('positions', [
                'id' => $id, 'area_id' => $this->areaIds[$d[1]], 'name' => $d[0], 'profile_category_id' => $d[2],
                'description' => 'Cargo de ' . $d[0] . ' del área de ' . $d[1] . ' en Organización Bless.',
                'settings' => $this->j(['polygraph' => $d[6], 'video_interview' => $d[7]]),
                'created_at' => $this->ts($now->subDays(700)), 'updated_at' => $this->ts($now->subDays($this->ri(5, 90))),
            ]);
            // requisitos
            $lvlMap = ['Media' => 30, 'Técnico' => 31, 'Tecnólogo' => 32, 'Profesional' => 33];
            $this->add('academic_requirements', ['position_id' => $id, 'academic_level_id' => $lvlMap[$d[10][0]], 'academic_title' => $d[10][1], 'formation_status_id' => 27, 'created_at' => $this->ts($now->subDays(690)), 'updated_at' => $this->ts($now->subDays(690))]);
            if ($d[2] == 63 && $this->chance(0.4)) {
                $this->add('academic_requirements', ['position_id' => $id, 'academic_level_id' => 34, 'academic_title' => 'Especialización deseable en área afín', 'formation_status_id' => 24, 'created_at' => $this->ts($now->subDays(690)), 'updated_at' => $this->ts($now->subDays(690))]);
            }
            foreach ($d[11] as $e) {
                $this->add('required_experiences', ['position_id' => $id, 'name' => $e[0], 'duration_months' => $e[1] > 60 ? 60 : $e[1], 'description' => 'Experiencia certificada mínima en ' . mb_strtolower($e[0]) . '.', 'created_at' => $this->ts($now->subDays(690)), 'updated_at' => $this->ts($now->subDays(690))]);
            }
            foreach ($d[9] as $s) {
                $sid = $this->C['skill'][$s];
                $pct = $this->skillType[$s] == 21 ? $this->ri(3, 5) : $this->ri(50, 95);
                $this->add('required_skills', ['position_id' => $id, 'skill_id' => $sid, 'percentage' => $pct, 'created_at' => $this->ts($now->subDays(690)), 'updated_at' => $this->ts($now->subDays(690))]);
            }
            foreach ($d[12] as $r) {
                [$n, $ds] = explode('|', $r);
                $this->add('responsabilities', ['position_id' => $id, 'name' => $n, 'description' => $ds, 'created_at' => $this->ts($now->subDays(690)), 'updated_at' => $this->ts($now->subDays(690))]);
            }
            // roles del cargo
            $this->posRoles($id, $d[8]);
        }

        // Estructura organizacional
        $parent = [
            'gg' => null, 'gth' => 'gg', 'asel' => 'gth', 'psi' => 'gth', 'anom' => 'gth', 'jprod' => 'gg', 'sup' => 'jprod', 'plana' => 'sup', 'overlock' => 'sup', 'term' => 'sup', 'lav' => 'sup',
            'dis' => 'jprod', 'pat' => 'dis', 'cort' => 'sup', 'tend' => 'sup', 'jcal' => 'gg', 'insp' => 'jcal', 'clog' => 'gg', 'abod' => 'clog', 'desp' => 'clog', 'gcom' => 'gg',
            'ejec' => 'gcom', 'aseso' => 'gcom', 'asc' => 'gcom', 'cont' => 'gg', 'auxc' => 'cont', 'cart' => 'cont', 'sis' => 'gg', 'mec' => 'jprod', 'cmed' => null, 'auxm' => 'cmed',
        ];
        foreach ($parent as $k => $p) {
            $ent = in_array($k, ['cmed', 'auxm']) ? 2 : 1;
            $status = 'Aprobado';
            if (in_array($k, ['psi', 'sis'])) {
                $status = 'Pendiente';
            }
            $this->add('organizational_structures', [
                'entity_id' => $this->entityIds[$ent], 'position_id' => $this->pos[$k]['id'], 'parent_position_id' => $p ? $this->pos[$p]['id'] : null,
                'start_date' => $this->dt($now->subDays($status == 'Pendiente' ? $this->ri(5, 25) : $this->ri(200, 600))),
                'end_date' => null, 'reason' => $p ? 'Reporta a ' . $this->pos[$p]['name'] . ' según el organigrama vigente.' : 'Cargo de máxima jerarquía de la entidad.',
                'status' => $status, 'created_at' => $this->ts($now->subDays(300)), 'updated_at' => $this->ts($now->subDays(20)),
            ]);
        }
    }

    private function posRoles(int $posId, string $lvl): void
    {
        $base = ['AbsenceManagement', 'VacationManagement', 'DisabilityManagement', 'LeaveManagement', 'CertificateManagement', 'SeveranceManagement', 'Navigation'];
        $validate = ['AbsenceValidate', 'VacationValidate', 'DisabilityValidate', 'LeaveValidate', 'CertificateValidate', 'SeveranceValidate', 'PersonnelRequisitionManagement', 'Employee'];
        $hr = ['Vacant', 'PersonnelRequisitionValidate', 'CandidateValidate', 'ScheduleInterviewInitial', 'ScheduleInterviewPsychotechnical', 'ScheduleTechnicalTest', 'SchedulePsychotechnicalTest', 'ScheduleMedicalExam',
            'SchedulePolygraphExam', 'ContractManagement', 'ContractValidate', 'AffiliationManagement', 'AffiliationValidate', 'Person', 'Position', 'Area', 'Skill', 'Form', 'Enterprise', 'NotificationType', 'WorkDay', 'WorkArrangement'];
        $names = match ($lvl) {
            'ger', 'hrl' => array_keys($this->roleIds),
            'hr' => array_merge($base, $validate, $hr),
            'lead' => array_merge($base, $validate),
            default => $base,
        };
        foreach (array_unique($names) as $n) {
            if (isset($this->roleIds[$n])) {
                $this->add('model_has_roles', ['role_id' => $this->roleIds[$n], 'model_type' => self::MH . 'Position', 'model_id' => $posId]);
            }
        }
    }

    // ------------------------------------------------------------ personas
    private const MALE = ['Juan', 'Carlos', 'Andrés', 'Luis', 'Jorge', 'Miguel', 'David', 'Santiago', 'Sebastián', 'Camilo', 'Daniel', 'Felipe', 'Jhon', 'Yeison', 'Edwin', 'Wilmer', 'Alexander', 'Cristian', 'Fabián', 'Néstor', 'Óscar', 'Hernán', 'Iván', 'Duván', 'Brayan', 'Kevin', 'Mauricio', 'Ricardo', 'Fernando', 'Julián', 'Nelson', 'Harold', 'Rafael', 'Víctor', 'Stiven', 'Eduardo', 'William', 'Gustavo', 'Álvaro', 'Leonardo', 'Jairo', 'Elkin', 'Nicolás', 'Yefferson'];
    private const FEMALE = ['María', 'Luisa', 'Paola', 'Andrea', 'Diana', 'Laura', 'Natalia', 'Carolina', 'Yuliana', 'Daniela', 'Valentina', 'Sandra', 'Claudia', 'Johana', 'Karen', 'Liliana', 'Marcela', 'Viviana', 'Yesenia', 'Leidy', 'Angie', 'Estefanía', 'Lorena', 'Paula', 'Mónica', 'Yolanda', 'Gloria', 'Nubia', 'Sofía', 'Camila', 'Katherine', 'Tatiana', 'Milena', 'Jennifer', 'Ana', 'Beatriz', 'Adriana', 'Ingrid', 'Rocío', 'Lucía', 'Yurley', 'Dayana', 'Mayerly'];
    private const SECOND_M = ['Alberto', 'Antonio', 'Javier', 'Fernando', 'Enrique', 'Andrés', 'David', 'Alejandro', 'Eduardo', 'José', 'Esteban', 'Arturo'];
    private const SECOND_F = ['Alejandra', 'Fernanda', 'Patricia', 'Marcela', 'Isabel', 'Camila', 'Lucía', 'Andrea', 'Cristina', 'Elena', 'Yamile', 'Milena'];
    private const SURN = ['Rodríguez', 'Gómez', 'Martínez', 'López', 'García', 'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Díaz', 'Vargas', 'Castro', 'Rojas', 'Moreno', 'Jiménez', 'Ortiz', 'Gutiérrez', 'Álvarez', 'Suárez', 'Mendoza', 'Quintero', 'Contreras', 'Peñaranda', 'Pabón', 'Villamizar', 'Carrascal', 'Mogollón', 'Duarte', 'Ortega', 'Rincón', 'Cáceres', 'Sepúlveda', 'Bautista', 'Ballesteros', 'Arévalo', 'Delgado', 'Herrera', 'Medina', 'Cardona', 'Zapata', 'Restrepo', 'Montoya', 'Londoño', 'Giraldo', 'Ospina', 'Agudelo', 'Gaviria', 'Henao', 'Osorio', 'Betancur', 'Tobón', 'Arango', 'Uribe', 'Salazar', 'Cano', 'Bedoya', 'Vélez', 'Franco', 'Mejía', 'Muñoz', 'Cárdenas', 'Parada', 'Lizarazo', 'Navarro', 'Hernández', 'González', 'Ruiz', 'Silva', 'Ardila', 'Becerra', 'Camargo', 'Chacón', 'Flórez', 'Jaimes', 'Lamus', 'Maldonado', 'Orozco', 'Prada', 'Reyes', 'Solano', 'Téllez', 'Velandia', 'Zambrano'];

    private function hoods(int $city): array
    {
        return match ($city) {
            20772 => ['Caobos', 'La Riviera', 'Quinta Vélez', 'Atalaya', 'San Luis', 'Cuberos Niño', 'Aniversario', 'Claret', 'El Salado', 'Ciudad Jardín', 'Prados del Este', 'Blanco', 'San Mateo', 'Motilones', 'Latino', 'Popular', 'Sevilla', 'Pescadero', 'La Libertad', 'Colsag', 'Guaimaral', 'Belén', 'Los Pinos', 'Palmeras'],
            21074 => ['El Poblado', 'Laureles', 'Belén', 'Robledo', 'Castilla', 'Buenos Aires', 'Manrique', 'Aranjuez', 'Guayabal', 'La América', 'Villa Hermosa', 'Calasanz', 'San Javier', 'Boston', 'Campo Valdés'],
            21601 => ['Centro', 'La Parada', 'Antonia Santos', 'Santa Teresita'],
            21035 => ['Centro', 'Patio Centro', 'Los Almendros', 'Santa Rita'],
            20953 => ['Santa María', 'San Pío X', 'Centro', 'Ditaires'],
            20579 => ['Niquía', 'Centro', 'Cabañas', 'París'],
            20840 => ['Zúñiga', 'Centro', 'Alcalá', 'Las Vegas'],
            21314 => ['Aves María', 'Centro', 'Pan de Azúcar'],
            default => ['Centro'],
        };
    }

    private function address(int $city): string
    {
        $n = fn($a, $b) => $this->ri($a, $b);
        if ($city == 20772 || $city == 21601 || $city == 21035) {
            return $this->pick(['Calle ' . $n(1, 30) . ' # ' . $n(1, 15) . '-' . $n(10, 99), 'Avenida ' . $n(1, 12) . 'E # ' . $n(1, 30) . '-' . $n(10, 99), 'Diagonal ' . $n(1, 20) . 'N # ' . $n(1, 15) . '-' . $n(10, 60), 'Transversal ' . $n(1, 9) . ' # ' . $n(1, 25) . '-' . $n(10, 80), 'Manzana ' . chr($n(65, 80)) . ' Casa ' . $n(1, 30)]);
        }
        return $this->pick(['Carrera ' . $n(30, 80) . 'A # ' . $n(1, 60) . '-' . $n(10, 99), 'Calle ' . $n(1, 80) . ' Sur # ' . $n(30, 60) . '-' . $n(10, 99), 'Circular ' . $n(1, 4) . ' # ' . $n(60, 80) . '-' . $n(10, 99), 'Transversal ' . $n(30, 45) . 'B # ' . $n(1, 60) . '-' . $n(10, 99), 'Diagonal ' . $n(50, 75) . ' # ' . $n(10, 60) . '-' . $n(10, 99)]);
    }

    private function docNumber(int $city, int $birthYear, int $type): string
    {
        do {
            if ($type === 12) {
                if ($birthYear < 1985) {
                    $n = (string) $this->pick([60, 88, 13, 37]) . str_pad((string) $this->ri(0, 999999), 6, '0', STR_PAD_LEFT);
                    if ($city == 21074) {
                        $n = (string) $this->pick([43, 71, 98, 70]) . str_pad((string) $this->ri(0, 999999), 6, '0', STR_PAD_LEFT);
                    }
                } else {
                    $pre = $city == 21074 ? $this->pick(['1037', '1017', '1001', '1035', '1152']) : $this->pick(['1090', '1093', '1004', '1005', '1094', '1091']);
                    $n = $pre . str_pad((string) $this->ri(0, 999999), 6, '0', STR_PAD_LEFT);
                }
            } elseif ($type === 13) {
                $n = (string) $this->ri(1000000, 99999999);
            } else {
                $n = (string) $this->ri(100000, 999999) . $this->ri(100, 999);
            }
        } while (isset($this->usedDoc[$n]));
        $this->usedDoc[$n] = 1;
        return $n;
    }

    /** Crea persona + usuario (+ meta). Opciones: sex, birth[a,b], city, employee(bool), created, first, second, last1, last2, domain */
    private function person(array $o = []): array
    {
        $sex = $o['sex'] ?? ($this->chance(0.45) ? 'M' : 'F');
        $city = $o['city'] ?? $this->weighted([20772 => 40, 21601 => 6, 21035 => 6, 21074 => 30, 20953 => 6, 20579 => 4, 20840 => 4, 21314 => 4]);
        $birth = $o['birth'] ?? [1972, 2005];
        $by = $this->ri($birth[0], $birth[1]);
        $bd = CarbonImmutable::create($by, $this->ri(1, 12), $this->ri(1, 28));
        $first = $o['first'] ?? ($sex === 'M' ? $this->pick(self::MALE) : $this->pick(self::FEMALE));
        $second = array_key_exists('second', $o) ? $o['second'] : ($this->chance(0.45) ? ($sex === 'M' ? $this->pick(self::SECOND_M) : $this->pick(self::SECOND_F)) : null);
        $l1 = $o['last1'] ?? $this->pick(self::SURN);
        $l2 = array_key_exists('last2', $o) ? $o['last2'] : ($this->chance(0.9) ? $this->pick(self::SURN) : null);

        $type = $o['doc'] ?? $this->weighted([12 => 93, 13 => 3, 17 => 4]);
        if ($type != 12) {
            $city = $o['city'] ?? $this->pick([20772, 20772, 21074]);
        }
        $doc = $this->docNumber($city, $by, (int) $type);
        $domain = $o['domain'] ?? (!empty($o['employee']) ? 'bless-demo.com' : 'example.com');
        $base = Str::lower(Str::ascii($first . '.' . $l1));
        do {
            $email = $base . ($this->emailSeq++ % 4 === 0 ? '' : $this->ri(1, 99)) . '@' . $domain;
        } while (isset($this->usedEmail[$email]));
        $this->usedEmail[$email] = 1;

        $created = $o['created'] ?? $this->ago(5, 480);
        $id = $this->nid('people');
        $issue = $bd->addYears(18)->addDays($this->ri(0, 400));
        if ($issue > $created) {
            $issue = $bd->addYears(18);
        }
        $ver = $created->addMinutes($this->ri(2, 60));
        $verified = $o['verified'] ?? $this->chance(0.9);
        $hoods = $this->hoods($city);
        $birthCity = $this->chance(0.7) ? $city : $this->pick([20772, 21074, 20603, 21176, 20634, 20574]);
        $this->add('people', [
            'id' => $id, 'first_name' => $first, 'second_name' => $second, 'first_last_name' => $l1, 'second_last_name' => $l2,
            'birth_date' => $this->dt($bd), 'birth_city_id' => $birthCity, 'document_type_id' => $type, 'document_issue_city_id' => $this->chance(0.6) ? $birthCity : $city,
            'document_number' => $doc, 'document_issue_date' => $this->dt($issue), 'city_id' => $city, 'address' => $this->address($city), 'neighborhood' => $this->pick($hoods),
            'dialing_country_id' => 48, 'phone_number' => $this->phone(), 'phone_number_verified_at' => $verified ? $this->ts($ver) : null,
            'email' => $email, 'email_verified_at' => $verified ? $this->ts($ver) : null,
            'sex_id' => $sex === 'M' ? 42 : 43,
            'gender_id' => $this->chance(0.97) ? ($sex === 'M' ? 44 : 45) : $this->pick([46, 47, 48]),
            'blood_type_id' => $this->weighted([55 => 46, 49 => 28, 51 => 8, 53 => 4, 50 => 4, 52 => 3, 56 => 5, 54 => 2]),
            'marital_status_id' => $this->weighted([57 => 42, 59 => 28, 58 => 22, 60 => 6, 61 => 2]),
            'created_at' => $this->ts($created), 'updated_at' => $this->ts($created->addDays($this->ri(0, 20)) > $this->now ? $this->now : $created->addDays($this->ri(0, 20))),
        ]);
        $this->add('users', ['id' => $this->nid('users'), 'person_id' => $id, 'password' => $this->pwd, 'remember_token' => null, 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);
        $meta = ['id' => $id, 'sex' => $sex, 'city' => $city, 'first' => $first, 'l1' => $l1, 'l2' => $l2, 'birth' => $bd, 'email' => $email, 'doc' => $doc, 'created' => $created, 'name' => trim("$first $l1"), 'full' => trim("$first " . ($second ? "$second " : '') . "$l1" . ($l2 ? " $l2" : ''))];
        $this->P[$id] = $meta;
        return $meta;
    }

    // ------------------------------------------------------------ perfil / hoja de vida
    private const SCHOOLS_C = ['Colegio Sagrado Corazón de Jesús', 'Institución Educativa Colegio Municipal de Cúcuta', 'Colegio Nuestra Señora de Belén', 'Instituto Técnico Cúcuta', 'Colegio Salesiano Cúcuta', 'Institución Educativa Pablo Correa León', 'Colegio Sagrado Corazón'];
    private const SCHOOLS_M = ['Institución Educativa Concejo de Medellín', 'Colegio San José de las Vegas', 'Institución Educativa Pedro Justo Berrío', 'Colegio Colombo Francés', 'Institución Educativa INEM José Félix de Restrepo'];
    private const COMPANIES = ['Confecciones Futuro Ltda.', 'Textiles del Norte S.A.S.', 'Jeans Oriente S.A.S.', 'Taller Manos Hábiles', 'Industrias Alfa Moda S.A.S.', 'Almacén Moda Total', 'Inversiones Catatumbo S.A.S.', 'Confecciones La Frontera', 'Moda Joven Cúcuta S.A.S.', 'Distribuciones El Sol', 'Maquilas del Valle S.A.S.', 'Comercializadora Andina del Norte', 'Supermercados La Económica', 'Tejidos y Confecciones Antioquia', 'Calzado y Moda del Oriente', 'Logística Express del Oriente S.A.S.', 'Inversiones Pacífico S.A.S.', 'Almacenes Éxito Local', 'Servicios Integrales Cúcuta Ltda.'];

    private function profile(array $p, string $track, int $yearsExp, ?array $posDef = null): void
    {
        $now = $this->now;
        $pid = $this->nid('profiles');
        $titles = [
            'ops' => ['Operario(a) de confección', 'Operaria de máquina plana', 'Operario de producción en confección', 'Auxiliar de producción textil'],
            'quality' => ['Inspector(a) de calidad textil', 'Auxiliar de control de calidad'],
            'logistics' => ['Auxiliar de bodega y despachos', 'Operario de logística'],
            'commercial' => ['Asesor(a) comercial y de ventas', 'Vendedor(a) de punto de venta', 'Ejecutivo(a) de ventas'],
            'admin' => ['Auxiliar administrativo(a)', 'Asistente contable y administrativo', 'Profesional en gestión humana', 'Analista administrativo'],
            'tech' => ['Técnico en soporte y sistemas', 'Ingeniero de sistemas'],
        ];
        $title = $posDef ? $posDef['name'] : $this->pick($titles[$track] ?? $titles['ops']);
        $desc = [
            "Persona responsable y comprometida con {$yearsExp} años de experiencia en el sector. Busco crecer profesionalmente en una empresa sólida y aportar mi experiencia en el trabajo diario.",
            "Cuento con experiencia comprobable y disposición para aprender. Me destaco por la puntualidad, el trabajo en equipo y la atención al detalle.",
            "Me motiva el trabajo bien hecho y cumplir metas. Disponibilidad de horario y de turnos rotativos.",
            "Interesado(a) en vincularme a una organización que valore el talento y ofrezca estabilidad laboral y oportunidades de formación.",
        ];
        $created = $p['created'];
        $this->add('profiles', ['id' => $pid, 'person_id' => $p['id'], 'title' => $title, 'description' => $this->pick($desc), 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);

        $schools = $p['city'] == 21074 || $p['city'] == 20953 || $p['city'] == 20579 || $p['city'] == 20840 || $p['city'] == 21314 ? self::SCHOOLS_M : self::SCHOOLS_C;
        $by = (int) $p['birth']->format('Y');
        $gradYear = min($by + 17, (int) $now->format('Y') - 1);
        $city = in_array($p['city'], [21074, 20953, 20579, 20840, 21314]) ? 21074 : 20772;
        // Media
        $this->add('academic_qualifications', ['profile_id' => $pid, 'city_id' => $city, 'academic_level_id' => 30, 'academic_institution' => $this->pick($schools), 'academic_title' => 'Bachiller Académico', 'formation_status_id' => 27, 'start_date' => ($gradYear - 5) . '-01-23', 'end_date' => $gradYear . '-11-30', 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);
        $titlesAc = [
            'ops' => [[31, 'SENA', 'Técnico en Confección Industrial de Ropa Exterior'], [31, 'SENA', 'Técnico en Operación de Máquinas de Confección'], [31, 'Servicio Nacional de Aprendizaje SENA', 'Técnico en Patronaje Industrial de Prendas de Vestir']],
            'quality' => [[31, 'SENA', 'Técnico en Control de Calidad Textil'], [32, 'SENA', 'Tecnólogo en Gestión de la Producción Industrial']],
            'logistics' => [[31, 'SENA', 'Técnico en Logística y Almacenamiento'], [32, 'Politécnico Colombiano Jaime Isaza Cadavid', 'Tecnología en Logística']],
            'commercial' => [[31, 'SENA', 'Técnico en Ventas de Productos y Servicios'], [32, 'SENA', 'Tecnólogo en Gestión Comercial y de Negocios'], [33, 'Universidad Simón Bolívar', 'Administración de Empresas'], [33, 'Universidad de Pamplona', 'Mercadeo']],
            'admin' => [[33, 'Universidad Francisco de Paula Santander', 'Contaduría Pública'], [33, 'Universidad de Antioquia', 'Administración de Empresas'], [32, 'SENA', 'Tecnólogo en Gestión Administrativa'], [33, 'Universidad de Pamplona', 'Psicología'], [33, 'EAFIT', 'Ingeniería de Producción'], [33, 'Universidad Cooperativa de Colombia', 'Contaduría Pública']],
            'tech' => [[33, 'Universidad Francisco de Paula Santander', 'Ingeniería de Sistemas'], [32, 'SENA', 'Tecnólogo en Análisis y Desarrollo de Software']],
        ];
        if ($track !== 'ops' || $this->chance(0.45)) {
            $a = $this->pick($titlesAc[$track] ?? $titlesAc['ops']);
            $len = $a[0] == 33 ? 5 : ($a[0] == 32 ? 3 : 2);
            $end = min($gradYear + $len + $this->ri(0, 2), (int) $now->format('Y') - 1);
            $this->add('academic_qualifications', ['profile_id' => $pid, 'city_id' => $city, 'academic_level_id' => $a[0], 'academic_institution' => $a[1], 'academic_title' => $a[2], 'formation_status_id' => $this->chance(0.9) ? 27 : 24, 'start_date' => ($end - $len) . '-02-01', 'end_date' => $end . '-12-10', 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);
        }
        if ($track === 'admin' && $this->chance(0.25)) {
            $this->add('academic_qualifications', ['profile_id' => $pid, 'city_id' => $city, 'academic_level_id' => 34, 'academic_institution' => 'Universidad Libre', 'academic_title' => 'Especialización en Gerencia del Talento Humano', 'formation_status_id' => 24, 'start_date' => ((int) $now->format('Y') - 1) . '-08-01', 'end_date' => null, 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);
        }

        // Experiencia
        $jobs = [
            'ops' => ['Operario de máquina plana', 'Operaria de confección', 'Operario de fileteadora', 'Auxiliar de producción', 'Operario de terminación'],
            'quality' => ['Inspector de calidad', 'Auxiliar de calidad'],
            'logistics' => ['Auxiliar de bodega', 'Alistador de pedidos', 'Operario de despachos'],
            'commercial' => ['Asesor de ventas', 'Vendedor de almacén', 'Cajero(a)', 'Auxiliar de ventas'],
            'admin' => ['Auxiliar administrativo', 'Asistente contable', 'Analista junior', 'Auxiliar de recursos humanos'],
            'tech' => ['Soporte técnico', 'Desarrollador junior'],
        ];
        $nJobs = $yearsExp <= 0 ? 0 : min(3, 1 + intdiv($yearsExp, 4));
        $end = $now->subMonths($this->ri(1, 6));
        $descs = ['Responsable de las funciones propias del cargo cumpliendo metas de calidad y productividad.', 'Cumplimiento de metas diarias, trabajo en equipo y reporte de novedades al jefe inmediato.', 'Apoyo en procesos operativos y administrativos, manejo de documentación y atención a clientes internos.'];
        for ($i = 0; $i < $nJobs; $i++) {
            $dur = $this->ri(10, 40);
            $start = $end->subMonths($dur);
            $this->add('professional_experiences', [
                'profile_id' => $pid, 'company_name' => $this->pick(self::COMPANIES), 'supervisor_name' => $this->pick(self::MALE) . ' ' . $this->pick(self::SURN), 'supervisor_dialing_country_id' => 48,
                'supervisor_phone_number' => $this->phone(), 'city_id' => $city, 'company_address' => $this->address($city), 'start_date' => $this->dt($start),
                'end_date' => ($i === 0 && $this->chance(0.25)) ? null : $this->dt($end), 'position' => $this->pick($jobs[$track] ?? $jobs['ops']),
                'work_arrangement_id' => 9, 'position_description' => $this->pick($descs), 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created),
            ]);
            $end = $start->subMonths($this->ri(0, 3));
        }

        // Habilidades
        $pools = [
            'ops' => ['Costura industrial en máquina plana', 'Manejo de fileteadora de 3 y 5 hilos', 'Lectura de fichas técnicas', 'Acabados y terminación de prendas', 'Atención al detalle', 'Trabajo en equipo', 'Responsabilidad y compromiso'],
            'quality' => ['Control de calidad textil', 'Atención al detalle', 'Lectura de fichas técnicas', 'Trabajo en equipo', 'Manejo de Excel'],
            'logistics' => ['Manejo de inventarios y bodega', 'Manejo de montacargas', 'Manejo de Excel', 'Responsabilidad y compromiso', 'Trabajo en equipo'],
            'commercial' => ['Atención al cliente en punto de venta', 'Facturación y manejo de caja', 'Ventas consultivas', 'Comunicación asertiva', 'Orientación al servicio', 'Negociación'],
            'admin' => ['Manejo de Excel', 'Contabilidad NIIF', 'Nómina y seguridad social', 'Manejo de ERP contable', 'Comunicación asertiva', 'Manejo del tiempo', 'Pensamiento analítico'],
            'tech' => ['Soporte técnico y redes', 'Resolución de problemas', 'Pensamiento analítico', 'Manejo de Excel'],
        ];
        $pool = $pools[$track] ?? $pools['ops'];
        shuffle($pool);
        foreach (array_slice($pool, 0, min(count($pool), $this->ri(3, 5))) as $s) {
            $this->add('professional_skills', ['profile_id' => $pid, 'skill_id' => $this->C['skill'][$s], 'percentage' => $this->skillType[$s] == 21 ? $this->ri(3, 5) : $this->ri(45, 98), 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);
        }
        // Idiomas
        $this->add('language_proficiencies', ['profile_id' => $pid, 'language_id' => 188, 'language_level_id' => 148, 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);
        if ($this->chance($track === 'ops' ? 0.12 : 0.5)) {
            $this->add('language_proficiencies', ['profile_id' => $pid, 'language_id' => $this->pick([186, 186, 186, 279, 196]), 'language_level_id' => $this->pick([142, 143, 143, 144, 145]), 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created)]);
        }
        // Contactos de emergencia
        $rels = [[379, 'M'], [380, 'F'], [383, null], [384, null], [385, 'M'], [386, 'F'], [381, 'M'], [382, 'F']];
        $nEc = $this->ri(1, 2);
        for ($i = 0; $i < $nEc; $i++) {
            $r = $this->pick($rels);
            $sx = $r[1] ?? $this->pick(['M', 'F']);
            $this->add('emergency_contacts', [
                'profile_id' => $pid, 'first_name' => $sx === 'M' ? $this->pick(self::MALE) : $this->pick(self::FEMALE), 'second_name' => null, 'first_last_name' => $p['l1'],
                'second_last_name' => $this->pick(self::SURN), 'relationship_id' => $r[0], 'dialing_country_id' => 48, 'phone_number' => $this->phone(), 'created_at' => $this->ts($created), 'updated_at' => $this->ts($created),
            ]);
        }
    }

    // ------------------------------------------------------------ empleados
    private function employees(): void
    {
        $now = $this->now;
        $plan = [];
        foreach ($this->pos as $k => $d) {
            for ($i = 0; $i < $d['n']; $i++) {
                $plan[] = $k;
            }
        }
        // retirados: claves de cargos operativos
        $retiredKeys = ['plana', 'plana', 'overlock', 'term', 'cort', 'aseso', 'abod', 'insp'];
        $retiredLeft = $retiredKeys;
        $seqByPos = [];
        $viaApp = 0;

        foreach ($plan as $k) {
            $d = $this->pos[$k];
            $seqByPos[$k] = ($seqByPos[$k] ?? 0) + 1;
            $retired = false;
            // retirados: la ultima unidad de ciertos cargos
            if (($key = array_search($k, $retiredLeft)) !== false && $seqByPos[$k] >= $d['n'] - (count(array_keys($retiredKeys, $k)) - 1) && $d['n'] > 1) {
                $retired = true;
                unset($retiredLeft[$key]);
            }
            $isLeader = in_array($d['lvl'], ['ger', 'hrl', 'lead']);
            if ($k === 'gg') {
                $hire = $now->subMonths(84);
            } elseif ($k === 'cmed') {
                $hire = $now->subMonths(30);
            } elseif ($isLeader) {
                $hire = $now->subMonths($this->ri(24, 80));
            } elseif ($retired) {
                $hire = $now->subMonths($this->ri(6, 36));
            } else {
                $hire = $this->chance(0.62) ? $now->subDays($this->ri(25, 395)) : $now->subMonths($this->ri(14, 60));
            }
            $hire = $hire->setTime(0, 0);
            while ($hire->isWeekend()) {
                $hire = $hire->subDay();
            }
            $via = true;
            if ($via) {
                $viaApp++;
            }

            $sex = in_array($k, ['aseso', 'asel', 'psi', 'anom', 'asc', 'auxc', 'auxm', 'term', 'insp', 'plana']) ? ($this->chance(0.72) ? 'F' : 'M') : ($this->chance(0.28) ? 'F' : 'M');
            $minBirth = $isLeader ? [1968, 1988] : [1980, 2004];
            // sede
            $hq = match ($d['track']) {
                'ops', 'quality', 'logistics' => $this->chance(0.75) ? 'plant' : 'taller',
                'commercial' => $this->chance(0.5) ? 'main' : 'med',
                default => $this->chance(0.82) ? 'main' : 'med',
            };
            if ($k === 'cmed' || $k === 'auxm') {
                $hq = 'med';
            }
            if (in_array($k, ['gg', 'gth', 'jprod', 'jcal', 'clog', 'gcom', 'cont', 'sup'])) {
                $hq = in_array($k, ['jprod', 'jcal', 'clog', 'sup']) ? 'plant' : 'main';
            }
            $city = in_array($hq, ['med', 'taller']) ? $this->weighted([21074 => 70, 20953 => 12, 20579 => 8, 20840 => 6, 21314 => 4]) : $this->weighted([20772 => 75, 21601 => 13, 21035 => 12]);

            $opts = ['sex' => $sex, 'birth' => $minBirth, 'city' => $city, 'employee' => true, 'created' => $hire->subDays($this->ri(55, 65))];
            if ($k === 'gg') {
                $p = $this->rep[1];
                $this->P[$p['id']]['created'] = $p['created'];
            } elseif ($k === 'cmed') {
                $p = $this->rep[2];
            } else {
                $p = $this->person($opts);
            }
            // empleados con registro anterior a 12 meses: fecha de creacion de persona = contratacion (sistema nuevo)
            $emp = ['person' => $p, 'pos' => $k, 'hq' => $hq, 'hire' => $hire, 'via' => $via, 'retired' => $retired, 'end' => null, 'id' => $this->nid('employees')];
            if ($retired) {
                $minEnd = $hire->addDays(60);
                $maxEnd = $now->subDays(10);
                $emp['end'] = $minEnd >= $maxEnd ? $maxEnd : $minEnd->addDays($this->ri(0, max(0, (int) $minEnd->diffInDays($maxEnd))));
            }
            $this->emp[] = $emp;
        }

        // Asegurar que representante 1 y 2 tienen hoja de vida
        $camp = $this->campaignIds;
        foreach ($this->emp as $i => &$e) {
            $d = $this->pos[$e['pos']];
            $years = max(1, (int) ($e['hire']->diffInYears($now)) + $this->ri(1, 5));
            $this->profile($e['person'], $d['track'], $years, $this->chance(0.35) ? $d : null);
            $campaign = null;
            if (in_array($e['pos'], ['aseso', 'ejec']) || ($d['track'] === 'ops' && $d['lvl'] === 'op' && $this->chance(0.6))) {
                $campaign = $this->pick($camp);
            }
            $e['campaign'] = $campaign;
            $e['salary'] = $d['sal'];
            $this->add('employees', [
                'id' => $e['id'], 'person_id' => $e['person']['id'], 'headquarter_id' => $this->hq[$e['hq']], 'position_id' => $d['id'], 'campaign_id' => $campaign,
                'start_date' => $this->dt($e['hire']), 'end_date' => $e['end'] ? $this->dt($e['end']) : null,
                'settings' => $this->j(['endowment' => ['top' => ['size_id' => $this->pick([415, 416, 417, 418])], 'bottom' => ['size_id' => $this->pick([404, 405, 406, 407, 408, 409])], 'footwear' => ['size_id' => $this->pick([405, 406, 407, 408, 409, 410])]], 'channels' => []]),
                'created_at' => $this->ts($e['hire']->addHours(8)), 'updated_at' => $this->ts($e['end'] ?? $e['hire']->addDays($this->ri(1, 30))),
            ]);
        }
        unset($e);
        $this->command?->info('Empleados: ' . count($this->emp) . ' (via aplicacion: ' . $viaApp . ')');
    }

    // ------------------------------------------------------------ candidatos
    private function candidates(): void
    {
        $dist = ['ops' => 52, 'logistics' => 14, 'commercial' => 26, 'admin' => 24, 'tech' => 4, 'quality' => 7];
        foreach ($dist as $track => $n) {
            for ($i = 0; $i < $n; $i++) {
                $created = $this->ago(3, 470);
                $o = ['created' => $created, 'birth' => $track === 'ops' ? [1974, 2006] : [1978, 2004], 'verified' => $this->chance(0.86)];
                if ($track === 'commercial' || $track === 'admin') {
                    $o['sex'] = $this->chance(0.62) ? 'F' : 'M';
                }
                $p = $this->person($o);
                $p['track'] = $track;
                $this->P[$p['id']]['track'] = $track;
                $years = $this->weighted([0 => 12, 1 => 15, 3 => 25, 6 => 25, 10 => 15, 14 => 8]);
                $this->profile($p, $track, $years);
                $this->cands[] = $p;
            }
        }
        shuffle($this->cands);
    }

    // ------------------------------------------------------------ formularios
    private function q(string $type, string $name, string $title, array $extra = []): array
    {
        return array_merge(['type' => $type, 'name' => $name, 'title' => $title, 'isRequired' => true], $extra);
    }

    private function formObj(string $title, string $desc, array $els): array
    {
        $pages = array_chunk($els, 5);
        $out = [];
        foreach ($pages as $i => $pg) {
            $out[] = ['name' => 'page' . ($i + 1), 'elements' => $pg];
        }
        return ['title' => $title, 'description' => $desc, 'pages' => $out];
    }

    private function addForm(string $key, int $typeId, string $title, string $desc, array $els, ?string $assignType = null, ?int $assignId = null, ?string $modelType = null, ?int $modelId = null): void
    {
        $id = $this->nid('forms');
        $q = $this->formObj($title, $desc, $els);
        $this->forms[$key] = ['id' => $id, 'q' => $q];
        $this->add('forms', [
            'id' => $id, 'form_type_id' => $typeId, 'assignable_type' => $assignType, 'assignable_id' => $assignId, 'model_type' => $modelType, 'model_id' => $modelId,
            'questions' => $this->j($q), 'created_at' => $this->ts($this->now->subDays($this->ri(120, 500))), 'updated_at' => $this->ts($this->now->subDays($this->ri(1, 100))),
        ]);
    }

    private function answers(array $q): array
    {
        $texts = ['Porque Bless es una empresa reconocida en la región y quiero crecer con ustedes.', 'Cuento con la experiencia y la disposición para aprender cada día.', 'Me gusta el trabajo en equipo y cumplir las metas que se proponen.', 'Busco estabilidad laboral y la oportunidad de capacitarme.', 'He trabajado varios años en el sector y conozco el proceso.', 'Soy una persona puntual, responsable y comprometida con mi trabajo.', 'Me interesa el cargo porque se ajusta a mi perfil y a mi formación.'];
        $a = [];
        foreach ($q['pages'] as $pg) {
            foreach ($pg['elements'] as $e) {
                $a[$e['name']] = match ($e['type']) {
                    'radiogroup', 'dropdown' => $this->pick($e['choices']),
                    'checkbox' => array_values(array_slice($e['choices'], 0, $this->ri(1, min(3, count($e['choices']))))),
                    'rating' => $this->ri(3, 5),
                    'boolean' => $this->chance(0.85),
                    'comment' => $this->pick($texts),
                    default => ($e['inputType'] ?? '') === 'number' ? (string) ($this->ri(18, 40) * 100000) : $this->pick($texts),
                };
            }
        }
        return $a;
    }

    private function makeForms(): void
    {
        $E = self::MH . 'Entity';
        $e1 = $this->entityIds[1];
        $A = self::MH . 'Area';
        $Pn = self::MH . 'Position';
        $exp = ['Menos de 1 año', '1 a 3 años', '3 a 5 años', 'Más de 5 años'];
        $common = fn(array $tools) => [
            $this->q('comment', 'motivacion', '¿Por qué le interesa trabajar en Bless?'),
            $this->q('radiogroup', 'turnos', '¿Tiene disponibilidad para trabajar en turnos rotativos?', ['choices' => ['Sí, tengo disponibilidad total', 'Solo turno diurno', 'No']]),
            $this->q('radiogroup', 'experiencia', '¿Cuánta experiencia tiene en un cargo similar?', ['choices' => $exp]),
            $this->q('radiogroup', 'inicio', '¿Cuándo podría iniciar labores?', ['choices' => ['Inmediatamente', 'En 15 días', 'En 30 días']]),
            $this->q('text', 'aspiracion', 'Aspiración salarial mensual (COP)', ['inputType' => 'number']),
            $this->q('checkbox', 'herramientas', '¿Qué herramientas o máquinas maneja?', ['choices' => $tools, 'isRequired' => false]),
        ];
        $this->addForm('app_prod', 134, 'Formulario de postulación - Producción', 'Cuéntanos sobre tu experiencia en confección.', $common(['Máquina plana', 'Fileteadora', 'Recubridora', 'Ojaladora', 'Botonadora', 'Plancha industrial']), $E, $e1, $A, $this->areaIds['Producción']);
        $this->addForm('app_com', 134, 'Formulario de postulación - Comercial', 'Cuéntanos sobre tu experiencia en ventas y servicio.', $common(['Punto de venta (POS)', 'Excel', 'CRM', 'Facturación electrónica', 'WhatsApp Business']), $E, $e1, $A, $this->areaIds['Comercial y Ventas']);
        $this->addForm('app_adm', 134, 'Formulario de postulación - Administrativo', 'Cuéntanos sobre tu formación y experiencia.', $common(['Excel avanzado', 'Siesa / ERP', 'Office 365', 'Power BI', 'Software contable']), $E, $e1, $A, $this->areaIds['Administración y Finanzas']);
        $this->addForm('app_gen', 134, 'Formulario de postulación general', 'Información general del postulante.', $common(['Excel', 'Office', 'Montacargas', 'Herramientas manuales']), $E, $e1);
        foreach (['Talento Humano', 'Logística y Bodega', 'Calidad', 'Corte y Diseño', 'Tecnología', 'Mantenimiento'] as $ar) {
            $this->addForm('app_' . $ar, 134, 'Formulario de postulación - ' . $ar, 'Información del postulante para el área de ' . $ar . '.', $common(['Excel', 'Office', 'Herramientas propias del cargo']), $E, $e1, $A, $this->areaIds[$ar]);
        }

        $this->addForm('int_ini', 138, 'Guía de entrevista inicial', 'Guía para la entrevista inicial de selección.', [
            $this->q('comment', 'trayectoria', 'Describa su trayectoria laboral reciente'),
            $this->q('comment', 'logro', 'Cuente un logro del que se sienta orgulloso(a)'),
            $this->q('radiogroup', 'residencia', '¿Reside cerca de la sede de trabajo?', ['choices' => ['Sí', 'No, necesita transporte']]),
            $this->q('rating', 'comunicacion', 'Calificación de comunicación', ['rateMax' => 5]),
            $this->q('rating', 'actitud', 'Calificación de actitud y presentación', ['rateMax' => 5]),
        ], $E, $e1);
        $this->addForm('int_psi', 138, 'Guía de entrevista psicotécnica', 'Entrevista por competencias.', [
            $this->q('comment', 'situacion', 'Describa una situación de conflicto en el trabajo y cómo la resolvió'),
            $this->q('comment', 'equipo', 'Cuente cómo se desenvuelve en equipos de trabajo'),
            $this->q('rating', 'estabilidad', 'Estabilidad emocional', ['rateMax' => 5]),
            $this->q('rating', 'responsabilidad', 'Responsabilidad y compromiso', ['rateMax' => 5]),
            $this->q('rating', 'tolerancia', 'Tolerancia a la presión', ['rateMax' => 5]),
        ], $E, $e1);
        $this->addForm('test_conf', 135, 'Prueba técnica - Operario de confección', 'Evaluación de conocimientos de costura industrial.', [
            $this->q('radiogroup', 't1', '¿Cuál es la puntada de seguridad más usada en el cierre de entrepierna del jean?', ['choices' => ['Puntada de cadeneta de doble hilo', 'Puntada recta simple', 'Zigzag', 'Pespunte invisible']]),
            $this->q('radiogroup', 't2', 'Si la máquina plana salta puntadas, lo primero que se revisa es:', ['choices' => ['La aguja (tipo, desgaste y posición)', 'El color del hilo', 'El voltaje de la planta', 'El tipo de tela únicamente']]),
            $this->q('radiogroup', 't3', '¿Qué aguja se recomienda para costura de denim pesado?', ['choices' => ['Aguja 110/18 punta jean', 'Aguja 60/8 punta bola', 'Aguja 70/10 universal', 'Aguja de doble punta 80']]),
            $this->q('radiogroup', 't4', 'La tolerancia de margen de costura (1 cm) permitida es de:', ['choices' => ['±1 mm', '±3 mm', '±1 cm', 'No hay tolerancia']]),
            $this->q('radiogroup', 't5', 'Un defecto crítico en una prenda es:', ['choices' => ['Costura abierta en entrepierna', 'Hilo suelto de 1 cm', 'Etiqueta ligeramente torcida', 'Variación leve de tono']]),
        ], $E, $e1, $Pn, $this->pos['plana']['id']);
        $this->addForm('test_ven', 135, 'Prueba técnica - Asesor de tienda', 'Evaluación de conocimientos de venta y caja.', [
            $this->q('radiogroup', 't1', 'Un cliente indeciso pide recomendación de jean. Lo primero es:', ['choices' => ['Preguntar uso, ajuste y estilo preferido', 'Mostrar el más costoso', 'Esperar a que decida solo', 'Mostrar todas las tallas']]),
            $this->q('radiogroup', 't2', 'Si el sistema no acepta el pago con tarjeta, usted:', ['choices' => ['Ofrece otro medio de pago y verifica el datáfono', 'Cancela la venta', 'Cobra sin registrar', 'Pide al cliente que vuelva mañana']]),
            $this->q('radiogroup', 't3', 'El cambio por talla se acepta dentro de:', ['choices' => ['30 días con etiqueta y factura', '3 días sin factura', 'No se aceptan cambios', '90 días sin etiqueta']]),
            $this->q('text', 't4', 'Calcule el valor con 15% de descuento de un jean de $189.900', ['inputType' => 'text']),
            $this->q('radiogroup', 't5', 'Al cierre de caja hay un faltante. Usted:', ['choices' => ['Reporta de inmediato al coordinador', 'Lo cubre con dinero propio sin avisar', 'Lo ignora', 'Ajusta el sistema']]),
        ], $E, $e1, $Pn, $this->pos['aseso']['id']);
        $this->addForm('test_bod', 135, 'Prueba técnica - Auxiliar de bodega', 'Evaluación de manejo de inventarios.', [
            $this->q('radiogroup', 't1', 'El método FIFO significa:', ['choices' => ['Primero en entrar, primero en salir', 'Último en entrar, primero en salir', 'Primero en salir sin registro', 'Fecha de inventario física']]),
            $this->q('radiogroup', 't2', 'Al recibir mercancía con diferencia frente a la remisión, usted:', ['choices' => ['Deja constancia y reporta al coordinador', 'Recibe sin novedad', 'Devuelve todo', 'Corrige la remisión']]),
            $this->q('radiogroup', 't3', 'La carga máxima de una estantería se respeta para:', ['choices' => ['Evitar accidentes y daños', 'Ahorrar espacio', 'Facilitar el conteo', 'Cumplir el horario']]),
            $this->q('text', 't4', 'Si hay 240 jeans y se despachan 5 cajas de 36, ¿cuántos quedan?', ['inputType' => 'text']),
        ], $E, $e1, $Pn, $this->pos['abod']['id']);
        $this->addForm('test_gen', 135, 'Prueba técnica general', 'Evaluación de conocimientos básicos del cargo.', [
            $this->q('radiogroup', 't1', 'Ante una instrucción poco clara de su jefe, usted:', ['choices' => ['Pide aclaración antes de actuar', 'Actúa por intuición', 'Espera que alguien más lo haga', 'La ignora']]),
            $this->q('text', 't2', 'Describa cómo organizaría su semana de trabajo con tres prioridades distintas', ['inputType' => 'text']),
            $this->q('radiogroup', 't3', 'Nivel de manejo de Excel', ['choices' => ['Básico', 'Intermedio', 'Avanzado']]),
            $this->q('text', 't4', 'Calcule el 19% de IVA sobre $1.250.000', ['inputType' => 'text']),
        ], $E, $e1);
        $this->addForm('test_psi', 136, 'Prueba psicotécnica - Competencias laborales', 'Cuestionario de competencias y comportamiento laboral.', [
            $this->q('radiogroup', 'p1', 'Prefiero trabajar:', ['choices' => ['En equipo', 'De forma independiente', 'Depende de la tarea']]),
            $this->q('radiogroup', 'p2', 'Cuando cometo un error:', ['choices' => ['Lo reporto y lo corrijo', 'Espero que nadie lo note', 'Culpo a otros', 'Me bloqueo']]),
            $this->q('rating', 'p3', 'Me adapto fácilmente a los cambios', ['rateMax' => 5]),
            $this->q('rating', 'p4', 'Mantengo la calma bajo presión', ['rateMax' => 5]),
            $this->q('rating', 'p5', 'Cumplo las normas aunque nadie me supervise', ['rateMax' => 5]),
            $this->q('radiogroup', 'p6', 'Mi relación con la autoridad es:', ['choices' => ['Respetuosa y colaborativa', 'Indiferente', 'Conflictiva']]),
        ], $E, $e1);
        $this->addForm('video', 140, 'Evaluación de video entrevista', 'Criterios para valorar la video entrevista.', [
            $this->q('rating', 'v1', 'Claridad al comunicarse', ['rateMax' => 5]),
            $this->q('rating', 'v2', 'Seguridad y presencia', ['rateMax' => 5]),
            $this->q('rating', 'v3', 'Coherencia de las respuestas', ['rateMax' => 5]),
            $this->q('comment', 'v4', 'Observaciones del evaluador', ['isRequired' => false]),
        ], $E, $e1);
        $this->addForm('clima', 137, 'Encuesta de clima laboral 2026', 'Tu opinión nos ayuda a mejorar. Es anónima para efectos de análisis.', [
            $this->q('rating', 'c1', 'Me siento orgulloso(a) de trabajar en Bless', ['rateMax' => 5]),
            $this->q('rating', 'c2', 'Mi jefe inmediato me trata con respeto', ['rateMax' => 5]),
            $this->q('rating', 'c3', 'Cuento con las herramientas necesarias para mi trabajo', ['rateMax' => 5]),
            $this->q('radiogroup', 'c4', '¿Recomendaría Bless como lugar de trabajo?', ['choices' => ['Sí, totalmente', 'Probablemente', 'No estoy seguro(a)', 'No']]),
            $this->q('comment', 'c5', '¿Qué mejoraría de su ambiente de trabajo?', ['isRequired' => false]),
        ], $E, $e1);
        $this->addForm('rating', 141, 'Calificación de la empresa', 'Cuéntanos cómo fue tu experiencia en el proceso de selección.', [
            $this->q('rating', 'r1', 'Claridad de la información recibida', ['rateMax' => 5]),
            $this->q('rating', 'r2', 'Trato del equipo de selección', ['rateMax' => 5]),
            $this->q('rating', 'r3', 'Agilidad del proceso', ['rateMax' => 5]),
            $this->q('comment', 'r4', 'Comentarios adicionales', ['isRequired' => false]),
        ], $E, $e1);

        // Inducciones: formularios de pasos
        $ind = [
            'corp' => ['Inducción corporativa Bless', 'Presentación de la empresa, cultura y normas generales para todo el personal nuevo.', $E, $e1, null, null, [
                ['Bienvenida y cultura Bless', 'Historia, misión, visión y valores corporativos.'],
                ['Reglamento interno de trabajo', 'Lectura y compromiso con el reglamento interno.'],
                ['Seguridad y salud en el trabajo', 'Normas SG-SST, uso de EPP y rutas de evacuación.'],
                ['Política de tratamiento de datos', 'Autorización y política de protección de datos personales.'],
                ['Tour por la planta y las sedes', 'Recorrido por las áreas y presentación del equipo.'],
            ]],
            'prod' => ['Inducción al área de Producción', 'Entrenamiento de ingreso para el personal de planta.', $E, $e1, $A, $this->areaIds['Producción'], [
                ['Proceso de confección del jean', 'Flujo de operaciones desde el corte hasta la terminación.'],
                ['Manejo seguro de maquinaria', 'Uso seguro de máquinas planas, fileteadoras y planchas.'],
                ['Estándares de calidad', 'Defectos críticos, mayores y menores; autocontrol.'],
                ['Metas y bonificación por productividad', 'Cómo se mide la eficiencia y se bonifica.'],
            ]],
            'com' => ['Inducción al área Comercial', 'Entrenamiento para asesores y ejecutivos comerciales.', $E, $e1, $A, $this->areaIds['Comercial y Ventas'], [
                ['Portafolio y colecciones', 'Conocimiento de la línea de jean hombre, mujer y accesorios.'],
                ['Técnicas de venta asesorada', 'Protocolo de atención y cierre de ventas.'],
                ['Manejo de caja y facturación', 'Facturación electrónica, cambios y garantías.'],
                ['Servicio al cliente y PQRS', 'Manejo de peticiones, quejas y reclamos.'],
            ]],
        ];
        $this->induction = [];
        foreach ($ind as $k => $d) {
            $iid = $this->nid('inductions');
            $this->add('inductions', ['id' => $iid, 'name' => $d[0], 'description' => $d[1], 'assignable_type' => $d[2], 'assignable_id' => $d[3], 'model_type' => $d[4], 'model_id' => $d[5], 'created_at' => $this->ts($this->now->subDays(400)), 'updated_at' => $this->ts($this->now->subDays(40))]);
            $steps = [];
            foreach ($d[6] as $i => $s) {
                $fkey = "step_{$k}_$i";
                $this->addForm($fkey, 139, $s[0], $s[1], [
                    $this->q('boolean', 'leido', 'Confirmo que leí y comprendí el contenido de este paso'),
                    $this->q('radiogroup', 'q1', 'Pregunta de verificación: ' . $s[0], ['choices' => ['Respuesta correcta', 'Respuesta parcial', 'No recuerdo']]),
                    $this->q('comment', 'dudas', 'Dudas o comentarios', ['isRequired' => false]),
                ], $E, $e1);
                $sid = $this->nid('steps');
                $steps[] = ['id' => $sid, 'form' => $fkey];
                $this->add('steps', ['id' => $sid, 'induction_id' => $iid, 'name' => $s[0], 'description' => $s[1], 'form_id' => $this->forms[$fkey]['id'], 'created_at' => $this->ts($this->now->subDays(400)), 'updated_at' => $this->ts($this->now->subDays(40))]);
            }
            $this->induction[$k] = $steps;
        }
    }

    private array $induction = [];

    // ------------------------------------------------------------ reclutamiento
    private function formFor(string $area): string
    {
        return match ($area) {
            'Producción' => 'app_prod', 'Comercial y Ventas' => 'app_com', 'Administración y Finanzas' => 'app_adm',
            'Gerencia General' => 'app_gen',
            default => 'app_' . $area,
        };
    }

    private function leaderOf(string $area): array
    {
        $map = ['Producción' => 'jprod', 'Comercial y Ventas' => 'gcom', 'Talento Humano' => 'gth', 'Calidad' => 'jcal', 'Logística y Bodega' => 'clog', 'Administración y Finanzas' => 'cont', 'Corte y Diseño' => 'dis', 'Tecnología' => 'gg', 'Mantenimiento' => 'jprod', 'Gerencia General' => 'gg'];
        foreach ($this->emp as $e) {
            if ($e['pos'] === ($map[$area] ?? 'gg')) {
                return $e['person'];
            }
        }
        return $this->emp[0]['person'];
    }

    private function hrPeople(): array
    {
        $r = [];
        foreach ($this->emp as $e) {
            if (in_array($e['pos'], ['asel', 'gth'])) {
                $r[] = $e['person'];
            }
        }
        return $r;
    }

    private function psychologists(): array
    {
        $r = [];
        foreach ($this->emp as $e) {
            if (in_array($e['pos'], ['psi'])) {
                $r[] = $e['person'];
            }
        }
        return $r ?: $this->hrPeople();
    }

    private function recruitment(): void
    {
        $now = $this->now;
        $hr = $this->hrPeople();
        $psy = $this->psychologists();

        // Vacantes -------------------------------------------------
        $mkVac = function (string $pk, string $status, CarbonImmutable $start, CarbonImmutable $end, int $qty, string $hqk, ?string $reasonOverride = null) use ($hr) {
            $d = $this->pos[$pk];
            $id = $this->nid('vacants');
            $leader = $this->leaderOf($d['area']);
            $sal = $d['sal'];
            $reason = $reasonOverride ?: $this->weighted([350 => 40, 351 => 20, 352 => 40]);
            $contract = $d['cat'] == 62 ? $this->weighted([1 => 70, 5 => 15, 2 => 15]) : $this->weighted([2 => 60, 1 => 30, 3 => 10]);
            $created = $start->subDays($this->ri(2, 9));
            $v = ['id' => $id, 'pk' => $pk, 'status' => $status, 'start' => $start, 'end' => $end, 'qty' => $qty, 'hq' => $hqk, 'poly' => $d['poly'], 'video' => $d['video'], 'area' => $d['area'], 'salary' => $sal, 'created' => $created, 'apps' => []];
            $this->vacants[] = $v;
            $this->add('vacants', [
                'id' => $id, 'uuid' => (string) Str::uuid(), 'hiring_reason_id' => $reason, 'position_id' => $d['id'], 'person_id' => $leader['id'], 'headquarter_id' => $this->hq[$hqk],
                'contract_type_id' => $contract, 'work_arrangement_id' => $d['track'] === 'admin' && $this->chance(0.25) ? 11 : 9,
                'work_day_id' => ($d['cat'] == 62 && $this->chance(0.3)) ? 8 : (($pk === 'aseso' && $this->chance(0.4)) ? 7 : 6),
                'salary_min' => (int) (round($sal * 0.97 / 1000) * 1000), 'salary_max' => (int) (round($sal * 1.12 / 1000) * 1000),
                'start_date' => $this->dt($start), 'end_date' => $this->dt($end),
                'description' => "Buscamos {$d['name']} para la {$this->hqName($hqk)}. Ofrecemos contrato directo con Organización Bless, afiliación a seguridad social y caja de compensación, y oportunidades de crecimiento. Se valora experiencia en el sector confección.",
                'quantity' => $qty, 'status' => $status, 'settings' => $this->j(['polygraph' => $d['poly'], 'video_interview' => $d['video']]),
                'created_at' => $this->ts($created), 'updated_at' => $this->ts($status === 'Finalizado' ? $end : $this->min($created->addDays($this->ri(1, 10)), $this->now)),
            ]);
            // beneficios
            $bens = array_values($this->C['benefit']);
            shuffle($bens);
            foreach (array_slice($bens, 0, $this->ri(3, 6)) as $b) {
                $this->link($b, self::MH . 'Vacant', $id);
            }
            foreach (array_slice($hr, 0, 2) as $h) {
                $this->add('model_has_people', ['person_id' => $h['id'], 'model_type' => self::MH . 'Vacant', 'model_id' => $id]);
            }
            return count($this->vacants) - 1;
        };

        // (a) Finalizadas por contrataciones
        $groups = [];
        foreach ($this->emp as $i => $e) {
            if ($e['via']) {
                $groups[$e['pos']][] = $i;
            }
        }
        foreach ($groups as $pk => $idxs) {
            // dividir en lotes de fechas cercanas (maximo 90 dias)
            usort($idxs, fn($a, $b) => $this->emp[$a]['hire'] <=> $this->emp[$b]['hire']);
            $batches = [];
            foreach ($idxs as $i) {
                $last = count($batches) - 1;
                if ($last >= 0 && abs($this->emp[$i]['hire']->diffInDays($this->emp[$batches[$last][0]]['hire'])) <= 70) {
                    $batches[$last][] = $i;
                } else {
                    $batches[] = [$i];
                }
            }
            foreach ($batches as $b) {
                $first = $this->emp[$b[0]]['hire'];
                $lastH = $this->emp[end($b)]['hire'];
                $vi = $mkVac($pk, 'Finalizado', $first->subDays($this->ri(48, 60)), $lastH->subDays($this->ri(8, 14)), count($b), $this->emp[$b[0]]['hq']);
                foreach ($b as $i) {
                    $this->emp[$i]['vac'] = $vi;
                }
            }
        }
        // (b) Abiertas
        $open = [['plana', 'plant'], ['plana', 'taller'], ['aseso', 'med'], ['cort', 'plant'], ['abod', 'plant'], ['ejec', 'main'], ['asel', 'main'], ['insp', 'plant'], ['cart', 'main'], ['overlock', 'plant']];
        foreach ($open as $o) {
            $st = $now->subDays($this->ri(28, 75));
            $mkVac($o[0], 'Publicado', $st, $now->addDays($this->ri(8, 40)), $this->ri(1, 4), $o[1]);
        }
        // (c) cerradas sin contratacion
        foreach ([['pat', 'plant'], ['auxc', 'main'], ['term', 'plant']] as $o) {
            $st = $now->subDays($this->ri(110, 260));
            $mkVac($o[0], 'Finalizado', $st, $st->addDays($this->ri(30, 50)), 1, $o[1]);
        }
        // (d) otros estados
        $mkVac('sup', 'Aprobado', $now->addDays(3), $now->addDays(33), 1, 'plant');
        $mkVac('dis', 'Aprobado', $now->addDays(5), $now->addDays(35), 1, 'main');
        $mkVac('anom', 'En revisión', $now->addDays(10), $now->addDays(40), 1, 'main');
        $mkVac('desp', 'En revisión', $now->addDays(12), $now->addDays(42), 2, 'plant');
        $mkVac('mec', 'En revisión', $now->addDays(15), $now->addDays(45), 1, 'plant');
        $mkVac('sis', 'Rechazado', $now->subDays(20), $now->addDays(10), 1, 'main');
        $mkVac('jprod', 'Rechazado', $now->subDays(70), $now->subDays(40), 1, 'plant');

        // Aplicaciones ----------------------------------------------
        $this->apps = [];
        $appliedPairs = [];
        // hired
        foreach ($this->emp as $i => $e) {
            if (!$e['via']) {
                continue;
            }
            $v = $this->vacants[$e['vac']];
            $created = $e['hire']->subDays($this->ri(38, 44))->setTime($this->ri(7, 20), $this->ri(0, 59));
            if ($created < $v['start']) {
                $created = $v['start']->setTime($this->ri(7, 20), 10);
            }
            $this->makeApp(['person' => $e['person'], 'vac' => $e['vac'], 'status' => 'Vinculado', 'cur' => 7, 'created' => $created, 'emp' => $i, 'hire' => $e['hire']]);
            $appliedPairs[$e['person']['id'] . '-' . $e['vac']] = 1;
            $this->emp[$i]['app'] = count($this->apps) - 1;
        }

        // no contratados
        $trackOf = fn(string $pk) => $this->pos[$pk]['track'] === 'ops' ? 'ops' : $this->pos[$pk]['track'];
        $available = [];
        foreach ($this->cands as $c) {
            $available[$c['track']][] = $c;
        }
        $cursor = [];
        $pickCand = function (int $vi) use (&$available, &$cursor, &$appliedPairs, $trackOf) {
            $t = $trackOf($this->vacants[$vi]['pk']);
            if (!isset($available[$t])) {
                $t = 'ops';
            }
            for ($n = 0; $n < count($available[$t]) * 2; $n++) {
                $cursor[$t] = (($cursor[$t] ?? -1) + 1) % count($available[$t]);
                $c = $available[$t][$cursor[$t]];
                if (!isset($appliedPairs[$c['id'] . '-' . $vi])) {
                    $appliedPairs[$c['id'] . '-' . $vi] = 1;
                    return $c;
                }
            }
            return null;
        };

        $openIdx = [];
        $closedIdx = [];
        foreach ($this->vacants as $vi => $v) {
            if ($v['status'] === 'Publicado') {
                $openIdx[] = $vi;
            } elseif ($v['status'] === 'Finalizado') {
                $closedIdx[] = $vi;
            }
        }
        // abiertas: pesos por popularidad
        $wOpen = [];
        foreach ($openIdx as $vi) {
            $wOpen[$vi] = in_array($this->vacants[$vi]['pk'], ['plana', 'aseso']) ? 14 : 7;
        }
        $statusPlan = array_merge(
            array_fill(0, 11, 'Pendiente'), array_fill(0, 11, 'En revisión'), array_fill(0, 8, 'Prueba'), array_fill(0, 5, 'Finalista'),
            array_fill(0, 5, 'Contratación'), array_fill(0, 7, 'Rechazado'), array_fill(0, 5, 'Desistió')
        );
        shuffle($statusPlan);
        foreach ($statusPlan as $st) {
            $vi = $this->weighted($wOpen);
            $c = $pickCand($vi);
            if (!$c) {
                continue;
            }
            $this->planApp($c, $vi, $st);
        }
        // cerradas: rechazados y desistidos
        $wClosed = [];
        foreach ($closedIdx as $vi) {
            $wClosed[$vi] = max(3, $this->vacants[$vi]['qty'] * 2 + 2);
        }
        $plan2 = array_merge(array_fill(0, 22, 'Rechazado'), array_fill(0, 10, 'Desistió'));
        shuffle($plan2);
        foreach ($plan2 as $st) {
            $vi = $this->weighted($wClosed);
            $c = $pickCand($vi);
            if (!$c) {
                continue;
            }
            $this->planApp($c, $vi, $st);
        }
    }

    private array $apps = [];

    private function hqName(string $k): string
    {
        return ['main' => 'Sede Principal Cúcuta', 'plant' => 'Planta de Producción Cúcuta', 'med' => 'Sede Medellín', 'taller' => 'Taller Satélite Medellín'][$k];
    }

    private function planApp(array $c, int $vi, string $status): void
    {
        $v = $this->vacants[$vi];
        $vacAge = (int) $v['start']->diffInDays($this->now);
        $minAge = [0 => 0, 1 => 1, 2 => 2, 3 => 6, 4 => 10, 5 => 17, 6 => 26];
        $cur = match ($status) {
            'Pendiente' => 0,
            'En revisión' => $this->pick([1, 2, 2, 3, 3]),
            'Prueba' => 4, 'Finalista' => 5, 'Contratación' => 6,
            'Rechazado' => $this->weighted([0 => 25, 2 => 25, 3 => 18, 4 => 14, 5 => 10, 6 => 3]),
            'Desistió' => $this->weighted([0 => 20, 2 => 22, 3 => 18, 4 => 18, 5 => 12, 6 => 10]),
        };
        // limitar por antiguedad de la vacante / ventana
        $window = $v['status'] === 'Finalizado' ? (int) $v['start']->diffInDays($v['end']) : $vacAge;
        while ($cur > 0 && $minAge[$cur] - 3 > $vacAge) {
            $cur--;
        }
        if ($status === 'Prueba' && $cur < 4) {
            $status = $cur >= 2 ? 'En revisión' : 'Pendiente';
        }
        if ($status === 'Finalista' && $cur < 5) {
            $status = $cur >= 2 ? 'En revisión' : 'Pendiente';
        }
        if ($status === 'Contratación' && $cur < 6) {
            $status = $cur >= 2 ? 'En revisión' : 'Pendiente';
        }
        // fecha de aplicacion
        if ($v['status'] === 'Finalizado') {
            $created = $v['start']->addDays($this->ri(0, max(1, $window)))->setTime($this->ri(7, 20), $this->ri(0, 59));
        } else {
            $maxAgeDays = max($minAge[$cur] - 3, 0);
            $age = $this->ri($maxAgeDays, max($maxAgeDays, $vacAge));
            $created = $this->now->subDays($age)->setTime($this->ri(7, 20), $this->ri(0, 59));
            if ($created > $this->now) {
                $created = $this->now->subHours(2);
            }
        }
        $this->makeApp(['person' => $c, 'vac' => $vi, 'status' => $status, 'cur' => $cur, 'created' => $created]);
    }

    private function makeApp(array $a): void
    {
        $now = $this->now;
        $v = $this->vacants[$a['vac']];
        $d = $this->pos[$v['pk']];
        $p = $a['person'];
        $status = $a['status'];
        $cur = $a['cur'];
        $created = $a['created'];
        $id = $this->nid('applications');
        $form = $this->forms[$this->formFor($v['area'])] ?? $this->forms['app_gen'];
        $answers = $this->answers($form['q']);
        $salary = (int) (round($d['sal'] * (0.98 + $this->ri(0, 14) / 100) / 1000) * 1000);
        if (isset($answers['aspiracion'])) {
            $answers['aspiracion'] = (string) $salary;
        }
        $obs = [
            'Pendiente' => null,
            'En revisión' => 'Perfil acorde a los requisitos del cargo. Continúa en proceso.',
            'Prueba' => 'Superó las entrevistas, pasa a pruebas técnica y psicotécnica.',
            'Finalista' => 'Candidato finalista. Pendiente de exámenes y antecedentes.',
            'Contratación' => 'Seleccionado. En trámite de documentación y contrato.',
            'Vinculado' => 'Proceso completo. Vinculado a la organización.',
            'Rechazado' => $this->pick(['No cumple con la experiencia requerida.', 'Resultado desfavorable en las pruebas.', 'Aspiración salarial por encima del rango del cargo.', 'No superó el examen médico ocupacional.', 'Perfil no ajustado a las necesidades de la vacante.']),
            'Desistió' => $this->pick(['El candidato desistió: aceptó otra oferta laboral.', 'El candidato no asistió a la cita programada y no respondió.', 'El candidato desistió por cambio de ciudad.', 'El candidato desistió por horarios de turno.']),
        ][$status];
        $lastUpd = $created->addDays($this->ri(0, 3));
        $this->add('applications', [
            'id' => $id, 'person_id' => $p['id'], 'vacant_id' => $v['id'], 'form_id' => $form['id'], 'questions' => $this->j($form['q']), 'answers' => $this->j($answers), 'salary' => $salary,
            'observation' => $obs, 'status' => $status, 'created_at' => $this->ts($created), 'updated_at' => $this->ts($this->min($lastUpd, $now)),
        ]);
        $this->apps[] = ['id' => $id, 'person' => $p, 'vac' => $a['vac'], 'status' => $status, 'cur' => $cur, 'created' => $created, 'salary' => $salary, 'emp' => $a['emp'] ?? null];
        $this->stages(end($this->apps), $v, $d);
    }

    private function stageDate(CarbonImmutable $created, int $offDays): CarbonImmutable
    {
        return $this->biz($created->addDays($offDays));
    }

    private function stages(array $app, array $v, array $d): void
    {
        $now = $this->now;
        $st = $app['status'];
        $cur = $app['cur'];
        $aid = $app['id'];
        $cr = $app['created'];
        $hr = $this->hrPeople();
        $psy = $this->psychologists();
        $leader = $this->leaderOf($v['area']);
        $off = [2 => 3, 3 => 7, 4 => 12, 5 => 19, 6 => 28];
        $person = $app['person'];

        // estado de cada etapa: ok | scheduled | done | fail | withdrawn
        $state = function (int $s) use ($st, $cur) {
            if ($s < $cur) {
                return 'ok';
            }
            if ($s > $cur) {
                return null;
            }
            return match ($st) {
                'Rechazado' => 'fail',
                'Desistió' => 'withdrawn',
                default => $this->chance(0.55) ? 'scheduled' : 'done',
            };
        };
        if ($st === 'Vinculado') {
            $state = fn(int $s) => 'ok';
        }
        $indications = ['Presentarse 10 minutos antes con cédula original.', 'Asistir con ropa cómoda y cédula original.', 'Presentarse en recepción de la sede y preguntar por Talento Humano.', 'Llevar hoja de vida impresa y copia de la cédula.'];
        $addr = $this->hqAddr($v['hq']);
        $salMin = (int) ($app['salary'] * 0.95);
        $salMax = (int) ($app['salary'] * 1.1);

        // 2. entrevista inicial
        if (($s = $state(2)) !== null) {
            $dt = $this->stageDate($cr, $off[2]);
            if ($s === 'scheduled' && $dt <= $now) {
                $dt = $now->addDays($this->ri(1, 3))->setTime($this->ri(8, 15), 0);
            }
            if ($s !== 'scheduled' && $dt > $now) {
                $dt = $now->subDays(1)->setTime($this->ri(8, 15), 0);
            }
            $hrp = $this->pick($hr);
            $q = $this->forms['int_ini']['q'];
            $done = in_array($s, ['ok', 'done', 'fail']);
            $row = [
                'application_id' => $aid, 'date_time' => $this->ts($dt), 'form_id' => $this->forms['int_ini']['id'], 'questions' => $this->j($q), 'answers' => $this->j($done ? $this->answers($q) : new \stdClass()),
                'salary_min' => $done ? $salMin : null, 'salary_max' => $done ? $salMax : null,
                'reviewer_observation' => null, 'reviewer_score' => null, 'reviewer_id' => $hrp['id'], 'reviewer_status' => 'Pendiente',
                'leader_observation' => null, 'leader_score' => null, 'leader_id' => $leader['id'], 'leader_status' => 'Pendiente',
                'indication' => $this->pick($indications) . " Lugar: $addr.", 'status' => 'Programado',
                'created_at' => $this->ts($this->min($dt->subDays(2), $now)), 'updated_at' => $this->ts($this->min($dt->addHours(3), $now)),
            ];
            if ($s === 'withdrawn') {
                $row['status'] = 'Desistió';
            } elseif ($s === 'scheduled') {
                $row['status'] = $this->chance(0.2) ? 'Reprogramado' : 'Programado';
            } else {
                $row['status'] = 'Realizado';
                $row['reviewer_observation'] = $s === 'fail' && $this->chance(0.5) ? 'Candidato con poca claridad en su experiencia. No recomendado.' : 'Buena disposición, comunicación clara y experiencia acorde al cargo.';
                $row['reviewer_score'] = $s === 'fail' ? $this->ri(1, 2) : $this->ri(3, 5);
                $row['reviewer_status'] = $s === 'fail' && $this->chance(0.5) ? 'Rechazado' : 'Aprobado';
                if ($row['reviewer_status'] === 'Aprobado' && $s !== 'done') {
                    $row['leader_observation'] = $s === 'fail' ? 'No cumple con el nivel técnico esperado.' : 'Cumple con el perfil. Recomendado para continuar.';
                    $row['leader_score'] = $s === 'fail' ? $this->ri(1, 2) : $this->ri(3, 5);
                    $row['leader_status'] = $s === 'fail' ? 'Rechazado' : 'Aprobado';
                }
                if ($s === 'done') {
                    $row['leader_status'] = 'Pendiente';
                }
            }
            $this->add('interview_initials', $row);
        }

        // 3. entrevista psicotecnica
        if (($s = $state(3)) !== null) {
            $dt = $this->stageDate($cr, $off[3]);
            if ($s === 'scheduled' && $dt <= $now) {
                $dt = $now->addDays($this->ri(1, 3))->setTime($this->ri(8, 15), 0);
            }
            if ($s !== 'scheduled' && $dt > $now) {
                $dt = $now->subDays(1)->setTime($this->ri(8, 15), 0);
            }
            $ps = $this->pick($psy);
            $q = $this->forms['int_psi']['q'];
            $done = in_array($s, ['ok', 'done', 'fail']);
            $row = [
                'application_id' => $aid, 'date_time' => $this->ts($dt), 'form_id' => $this->forms['int_psi']['id'], 'questions' => $this->j($q), 'answers' => $this->j($done ? $this->answers($q) : new \stdClass()),
                'psychologist_observation' => null, 'psychologist_score' => null, 'psychologist_id' => $ps['id'], 'psychologist_status' => 'Pendiente',
                'indication' => $this->pick($indications) . " Lugar: $addr.", 'status' => 'Programado',
                'created_at' => $this->ts($this->min($dt->subDays(2), $now)), 'updated_at' => $this->ts($this->min($dt->addHours(2), $now)),
            ];
            if ($s === 'withdrawn') {
                $row['status'] = 'Desistió';
            } elseif ($s === 'scheduled') {
                $row['status'] = $this->chance(0.15) ? 'Reprogramado' : 'Programado';
            } else {
                $row['status'] = 'Realizado';
                if ($s !== 'done') {
                    $row['psychologist_observation'] = $s === 'fail' ? 'Dificultad para manejo de la autoridad y bajo compromiso. No recomendado.' : 'Perfil estable, buena tolerancia a la presión y orientación al trabajo en equipo.';
                    $row['psychologist_score'] = $s === 'fail' ? $this->ri(1, 2) : $this->ri(3, 5);
                    $row['psychologist_status'] = $s === 'fail' ? 'Rechazado' : 'Aprobado';
                }
            }
            $this->add('interview_psychotechnicals', $row);
        }

        // 4. pruebas tecnica y psicotecnica
        if (($s = $state(4)) !== null) {
            $dtStart = $this->stageDate($cr, $off[4]);
            $dtEnd = $dtStart->addDays(3);
            $tkey = match ($v['pk']) {
                'plana', 'overlock', 'term', 'lav' => 'test_conf', 'aseso', 'ejec', 'asc' => 'test_ven', 'abod', 'desp' => 'test_bod', default => 'test_gen',
            };
            $mk = function (string $kind) use ($s, $dtStart, $dtEnd, $now, $aid, $tkey, $leader, $psy, $addr) {
                $q = $this->forms[$kind === 'tech' ? $tkey : 'test_psi']['q'];
                $fid = $this->forms[$kind === 'tech' ? $tkey : 'test_psi']['id'];
                $status = 'Pendiente';
                $startAt = null;
                $endAt = null;
                $ans = new \stdClass();
                $obs = null;
                $evalSt = 'Pendiente';
                if ($s === 'ok' || $s === 'fail' || $s === 'done') {
                    $startAt = $dtStart->addHours($this->ri(1, 40));
                    if ($startAt > $now) {
                        $startAt = $now->subDays(1);
                    }
                    $endAt = $startAt->addMinutes($this->ri(18, 55));
                    $status = 'Finalizado';
                    $ans = $this->answers($q);
                    if ($s !== 'done') {
                        $obs = $s === 'fail' ? 'Resultado por debajo del puntaje mínimo exigido.' : 'Resultado satisfactorio, cumple el puntaje requerido.';
                        $evalSt = $s === 'fail' ? 'Rechazado' : 'Aprobado';
                    }
                } elseif ($s === 'scheduled') {
                    $status = $this->chance(0.4) ? 'En curso' : 'Pendiente';
                    if ($status === 'En curso') {
                        $startAt = $now->subHours($this->ri(1, 20));
                    }
                } elseif ($s === 'withdrawn') {
                    $status = $this->chance(0.4) ? 'Expirado' : 'Pendiente';
                }
                $row = [
                    'application_id' => $aid, 'start_date' => $this->dt($dtStart), 'end_date' => $this->dt($dtEnd), 'form_id' => $fid, 'questions' => $this->j($q), 'answers' => $this->j($ans),
                    'start_at' => $startAt ? $this->ts($startAt) : null, 'end_at' => $endAt ? $this->ts($endAt) : null, 'indication' => 'Realice la prueba en línea con buena conexión a internet. Dispone de 60 minutos.',
                    'status' => $status, 'created_at' => $this->ts($this->min($dtStart->subDays(1), $now)), 'updated_at' => $this->ts($this->min($dtEnd, $now)),
                ];
                if ($kind === 'tech') {
                    $row += ['leader_observation' => $obs, 'leader_id' => $leader['id'], 'leader_status' => $evalSt];
                } else {
                    $row += ['psychologist_observation' => $obs, 'psychologist_id' => $this->pick($psy)['id'], 'psychologist_status' => $evalSt];
                }
                return $row;
            };
            $this->add('test_technicals', $mk('tech'));
            $this->add('test_psychotechnicals', $mk('psy'));
        }
        // video entrevista (si el cargo lo exige y paso de entrevista inicial)
        if ($v['video'] && $cur >= 3 && $st !== 'Pendiente') {
            $q = $this->forms['video']['q'];
            $this->add('interview_videos', [
                'application_id' => $aid, 'form_id' => $this->forms['video']['id'], 'questions' => $this->j($q), 'answers' => $this->j($this->answers($q)),
                'reviewer_observation' => 'Se expresa con claridad y seguridad.', 'reviewer_id' => $this->pick($hr)['id'], 'status' => ($st === 'Rechazado' && $cur == 3) ? 'Rechazado' : 'Aprobado',
                'created_at' => $this->ts($this->min($cr->addDays(2), $now)), 'updated_at' => $this->ts($this->min($cr->addDays(3), $now)),
            ]);
        }

        // 5. examenes y antecedentes
        if (($s = $state(5)) !== null) {
            $dt = $this->stageDate($cr, $off[5]);
            if ($s === 'scheduled' && $dt <= $now) {
                $dt = $now->addDays($this->ri(1, 4))->setTime($this->ri(8, 15), 0);
            }
            if ($s !== 'scheduled' && $dt > $now) {
                $dt = $now->subDays(1)->setTime($this->ri(8, 14), 0);
            }
            $isMed = in_array($v['hq'], ['med', 'taller']);
            $city = $isMed ? 21074 : 20772;
            $ips = $isMed ? $this->pick([$this->C['health']['Medicina Laboral Antioquia IPS (Medellín)'], $this->C['health']['IPS Vida Laboral Medellín']]) : $this->pick([$this->C['health']['IPS Salud Ocupacional del Oriente (Cúcuta)'], $this->C['health']['Centro Médico Ocupacional Norte (Cúcuta)']]);
            $med = ['application_id' => $aid, 'date_time' => $this->ts($dt), 'city_id' => $city, 'health_entity_id' => $ips, 'address' => $isMed ? 'Carrera 48 # 19A-40' : 'Avenida 4E # 14-22', 'neighborhood' => $isMed ? 'El Poblado' : 'Caobos', 'result' => 'Pendiente', 'epidemiological_program_id' => null, 'description' => null, 'indication' => 'Asistir en ayunas de 8 horas. Llevar cédula original y orden de examen.', 'status' => 'Programado', 'created_at' => $this->ts($this->min($dt->subDays(2), $now)), 'updated_at' => $this->ts($this->min($dt->addHours(4), $now))];
            $poly = ['application_id' => $aid, 'date_time' => $this->ts($dt->addDay()), 'city_id' => $city, 'service_provider_id' => $this->pick(array_values($this->C['provider'])), 'address' => $isMed ? 'Calle 33 # 74B-20' : 'Calle 13 # 3-45 Of. 302', 'neighborhood' => $isMed ? 'Laureles' : 'Centro', 'result' => 'Pendiente', 'description' => null, 'indication' => 'No consumir alcohol 24 horas antes. Dormir bien la noche anterior.', 'status' => 'Programado', 'created_at' => $this->ts($this->min($dt->subDays(1), $now)), 'updated_at' => $this->ts($this->min($dt->addDay(), $now))];
            if ($s === 'withdrawn') {
                $med['status'] = 'Desistió';
                $poly['status'] = 'Desistió';
            } elseif ($s === 'scheduled') {
                $med['status'] = $this->chance(0.2) ? 'Reprogramado' : 'Programado';
            } else {
                $med['status'] = 'Realizado';
                if ($s === 'fail') {
                    $med['result'] = $this->weighted(['No apto' => 40, 'Apto con recomendaciones' => 10, 'Aplazado' => 10]);
                    $med['description'] = 'Hallazgos que contraindican el desempeño del cargo.';
                } else {
                    $med['result'] = $this->weighted(['Apto' => 70, 'Apto con recomendaciones' => 30]);
                    if ($med['result'] === 'Apto con recomendaciones') {
                        $med['epidemiological_program_id'] = $this->pick(array_values($this->C['epi']));
                        $med['description'] = 'Se recomienda vinculación al programa de vigilancia epidemiológica y pausas activas.';
                    }
                }
                $poly['status'] = 'Realizado';
                $poly['result'] = $s === 'fail' && $med['result'] !== 'No apto' ? 'No apto' : 'Apto';
                if ($s === 'done') {
                    $med['result'] = 'Pendiente';
                    $poly['result'] = 'Pendiente';
                }
            }
            $this->add('exam_medicals', $med);
            if ($v['poly']) {
                $this->add('exam_polygraphs', $poly);
            }
            // antecedentes
            $this->add('criminal_records', [
                'application_id' => $aid, 'observation' => $s === 'fail' && $this->chance(0.3) ? 'Registra anotaciones que impiden la vinculación.' : 'Sin antecedentes judiciales, disciplinarios ni fiscales vigentes.',
                'status' => $s === 'ok' ? 'Aprobado' : ($s === 'fail' ? $this->pick(['Rechazado', 'Aprobado']) : 'En revisión'),
                'created_at' => $this->ts($this->min($cr->addDays(14), $now)), 'updated_at' => $this->ts($this->min($cr->addDays(17), $now)),
            ]);
        } elseif ($cur > 5 || $st === 'Vinculado') {
            // no ocurre
        }

        // 6. documentos / afiliaciones (solo personas aun sin afiliaciones)
        $docState = null;
        if ($cur >= 6 || $st === 'Vinculado') {
            $docState = $st === 'Vinculado' ? 'ok' : ($st === 'Desistió' ? 'wd' : 'wip');
            $this->affiliations($person, $docState, $cr->addDays(26));
        }
    }

    private function hqAddr(string $k): string
    {
        return ['main' => 'Avenida 5 # 12-50, Centro, Cúcuta', 'plant' => 'Anillo Vial Oriental Km 3, Zona Industrial, Cúcuta', 'med' => 'Calle 10 # 43D-30, El Poblado, Medellín', 'taller' => 'Carrera 50 # 10 Sur-85, Guayabal, Medellín'][$k];
    }

    private array $affDone = [];

    private function affiliations(array $person, string $mode, CarbonImmutable $when): void
    {
        $pid = $person['id'];
        if (isset($this->affDone[$pid])) {
            return;
        }
        $this->affDone[$pid] = 1;
        $now = $this->now;
        $when = $when > $now ? $now->subDays(1) : $when;
        $statusFn = function () use ($mode) {
            return match ($mode) {
                'ok' => 'Aprobado',
                'wd' => $this->weighted(['Pendiente' => 40, 'Aprobado' => 40, 'Actualizar' => 20]),
                default => $this->weighted(['Aprobado' => 55, 'Pendiente' => 25, 'Actualizar' => 20]),
            };
        };
        $fix = ['La imagen del documento no es legible, por favor cárgalo nuevamente.', 'El documento está incompleto. Adjunta todas las páginas.', 'La fecha del certificado supera los 30 días. Actualízalo.'];
        $row = fn(array $extra) => $extra + ['person_id' => $pid, 'country_id' => 48, 'created_at' => $this->ts($when), 'updated_at' => $this->ts($when->addDays($this->ri(0, 3)) > $now ? $now : $when->addDays($this->ri(0, 3)))];
        $mk = function (string $table, array $extra) use ($statusFn, $fix, $row) {
            $s = $statusFn();
            $this->add($table, $row($extra + ['status' => $s, 'observation' => $s === 'Actualizar' ? $this->pick($fix) : null]));
        };
        $mk('health_entity_affiliations', ['health_entity_id' => $this->pick(array_slice(array_values($this->C['health']), 0, 10))]);
        $mk('pension_fund_affiliations', ['pension_fund_id' => $this->pick(array_values($this->C['pension']))]);
        $ccf = in_array($person['city'], [20772, 21601, 21035]) ? $this->C['ccf']['Comfaoriente'] : $this->pick([$this->C['ccf']['Comfama'], $this->C['ccf']['Comfenalco Antioquia']]);
        $mk('compensation_fund_affiliations', ['compensation_fund_id' => $ccf]);
        $mk('risk_manager_affiliations', ['risk_manager_id' => $this->pick(array_values($this->C['arl']))]);
        $s = $statusFn();
        $this->add('bank_certificates', $row(['bank_id' => $this->pick([67, 67, 73, 70, 66, 64, 72, 75]), 'bank_account_type_id' => $this->pick([94, 94, 94, 95]), 'account_number' => (string) $this->ri(100000000, 999999999) . $this->ri(10, 99), 'observation' => $s === 'Actualizar' ? 'El certificado bancario debe tener fecha no mayor a 30 días.' : null, 'status' => $s]));
        $s = $statusFn();
        $this->add('fiscal_document_certificates', $row(['fiscal_document_id' => $this->C['fiscal']['RUT - Registro Único Tributario'], 'observation' => $s === 'Actualizar' ? $this->pick($fix) : null, 'status' => $s]));
    }

    // ------------------------------------------------------------ contratos
    private function contracts(): void
    {
        $now = $this->now;
        $rep1 = $this->rep[1]['id'];
        $cmap = [];
        $psKeys = ['cont', 'dis', 'sis', 'pat'];
        $renewalsDone = 0;
        $nearEnd = 0;
        $contractRows = [];
        foreach ($this->emp as $i => $e) {
            $d = $this->pos[$e['pos']];
            $type = $d['cat'] == 62 ? $this->weighted([1 => 58, 2 => 37, 5 => 5]) : (in_array($e['pos'], $psKeys) ? 3 : $this->weighted([2 => 72, 1 => 28]));
            if (in_array($e['pos'], ['gg', 'cmed', 'gth'])) {
                $type = 2;
            }
            if ($type === 3 && $d['lvl'] !== 'op' && $e['pos'] !== 'cont') {
                $type = $this->pick([2, 3]);
            }
            $hire = $e['hire'];
            if ($type === 1 && $hire < $now->subMonths(27)) {
                $type = 2;
            }
            $end = null;
            if ($type === 1 || $type === 3 || $type === 5) {
                $end = $hire->addMonths($type === 5 ? 6 : 12);
            }
            if ($e['retired'] && $e['end']) {
                $end = $e['end'];
            }
            $salary = $e['salary'];
            $cid = $this->nid('contracts');
            $e['contract'] = $cid;
            $sig = $hire->subDays(3)->setTime($this->ri(8, 17), $this->ri(0, 59));
            $row = [
                'id' => $cid, 'uuid' => (string) Str::uuid(), 'application_id' => isset($e['app']) ? $this->apps[$e['app']]['id'] : null, 'person_id' => $e['person']['id'], 'headquarter_id' => $this->hq[$e['hq']],
                'contract_type_id' => $type, 'position_id' => $d['id'], 'salary' => $salary, 'transport_allowance' => $salary <= self::SMMLV * 2 ? self::TRANSPORT : 0, 'payment_period_id' => $d['cat'] == 62 ? $this->weighted([356 => 60, 354 => 40]) : 354,
                'start_date' => $this->dt($hire), 'end_date' => $end ? $this->dt($end) : null, 'person_observation' => null, 'person_signed_at' => $this->ts($sig), 'person_status' => 'Firmado',
                'legal_representative_signed_at' => $this->ts($sig->addHours($this->ri(1, 20))), 'legal_representative_status' => 'Firmado',
                'settings' => $this->j(['accept_terms' => true, 'accept_privacy_policy' => true, 'templates' => []]),
                'created_at' => $this->ts($sig->subDays(2)), 'updated_at' => $this->ts($sig->addDays(1)),
            ];
            $contractRows[] = $row;
            $this->emp[$i]['contract'] = $cid;
            $this->emp[$i]['ctype'] = $type;
            $this->emp[$i]['cend'] = $end;

            // renovaciones por otrosi cuando el contrato a termino fijo ya vencio
            $guard = 0;
            while ($end && $end < $now && !$e['retired'] && $guard++ < 3) {
                $newEnd = $end->addMonths(12);
                $this->addendum($cid, 'Prórroga del contrato', ['end_date' => $newEnd, 'start_date' => $end->addDay()], 'ok', $end->subDays(30));
                $end = $newEnd;
                $this->emp[$i]['cend'] = $newEnd;
                $renewalsDone++;
            }
        }
        // contratos para aplicaciones en etapa de contratacion
        foreach ($this->apps as $ai => $a) {
            if (!in_array($a['status'], ['Contratación']) && !($a['status'] === 'Desistió' && $a['cur'] === 6)) {
                continue;
            }
            $v = $this->vacants[$a['vac']];
            $d = $this->pos[$v['pk']];
            $start = $this->now->addDays($this->ri(5, 20));
            $type = $this->weighted([1 => 60, 2 => 30, 5 => 10]);
            $mode = $a['status'] === 'Desistió' ? ['Desistió', 'En revisión', null, null] : $this->pick([['Pendiente', 'En revisión', null, null], ['Pendiente', 'En revisión', null, null], ['Actualizar', 'En revisión', null, null], ['Firmado', 'Aprobado', 1, null], ['Firmado', 'Aprobado', 1, null]]);
            $created = $this->min($a['created']->addDays(28), $now->subHours(3));
            $pSigned = $mode[0] === 'Firmado' ? $this->min($created->addDays(1), $now->subHours(1)) : null;
            $cid = $this->nid('contracts');
            $contractRows[] = [
                'id' => $cid, 'uuid' => (string) Str::uuid(), 'application_id' => $a['id'], 'person_id' => $a['person']['id'], 'headquarter_id' => $this->hq[$v['hq']],
                'contract_type_id' => $type, 'position_id' => $d['id'], 'salary' => $a['salary'], 'transport_allowance' => $a['salary'] <= self::SMMLV * 2 ? self::TRANSPORT : 0, 'payment_period_id' => 354,
                'start_date' => $this->dt($start), 'end_date' => $type === 2 ? null : $this->dt($start->addMonths(12)),
                'person_observation' => $mode[0] === 'Actualizar' ? 'Por favor corregir mi segundo apellido en el contrato.' : ($mode[0] === 'Desistió' ? 'Acepté otra oferta, no continuaré el proceso.' : null),
                'person_signed_at' => $pSigned ? $this->ts($pSigned) : null, 'person_status' => $mode[0], 'legal_representative_signed_at' => null, 'legal_representative_status' => $mode[1],
                'settings' => $this->j(['accept_terms' => $mode[0] === 'Firmado', 'accept_privacy_policy' => $mode[0] === 'Firmado', 'templates' => []]),
                'created_at' => $this->ts($created), 'updated_at' => $this->ts($created),
            ];
        }
        $this->ins2('contracts', $contractRows);

        // otrosi adicionales (cambios salariales, cargo, sede, pendientes)
        $active = array_values(array_filter($this->emp, fn($e) => !$e['retired'] && $e['hire'] < $now->subMonths(4)));
        shuffle($active);
        $k = 0;
        foreach (array_slice($active, 0, 8) as $e) {
            $this->addendum($e['contract'], 'Ajuste salarial', ['salary' => (int) round($e['salary'] * 1.09 / 1000) * 1000, 'start_date' => CarbonImmutable::create((int) $now->format('Y'), 1, 1)], 'ok', CarbonImmutable::create((int) $now->format('Y'), 1, 1)->subDays(5));
        }
        foreach (array_slice($active, 8, 3) as $e) {
            $this->addendum($e['contract'], 'Traslado de sede', ['headquarter_id' => $this->hq[$e['hq'] === 'plant' ? 'taller' : ($e['hq'] === 'main' ? 'med' : 'main')], 'start_date' => $now->subDays($this->ri(20, 120))], 'ok', $now->subDays($this->ri(25, 125)));
        }
        foreach (array_slice($active, 11, 2) as $e) {
            $this->addendum($e['contract'], 'Cambio de cargo', ['position_id' => $this->pos['sup']['id'], 'salary' => $this->pos['sup']['sal'], 'start_date' => $now->addDays(10)], 'pending', $now->subDays(4));
        }
        $this->addendum($active[13]['contract'], 'Cambio de tipo de contrato', ['contract_type_id' => 2, 'start_date' => $now->addDays(15)], 'rep', $now->subDays(2));
        $this->addendum($active[14]['contract'], 'Ajuste salarial', ['salary' => (int) round($active[14]['salary'] * 1.07 / 1000) * 1000, 'start_date' => $now->addDays(20)], 'update', $now->subDays(6));
        $this->addendum($active[15]['contract'], 'Traslado de sede', ['headquarter_id' => $this->hq['med'], 'start_date' => $now->addDays(25)], 'withdrawn', $now->subDays(9));
        $this->addendum($active[16]['contract'], 'Ajuste salarial', ['salary' => (int) round($active[16]['salary'] * 1.05 / 1000) * 1000, 'start_date' => $now->subDays(30)], 'rejected', $now->subDays(40));

        // polizas para contratos de prestacion de servicios
        $ps = array_values(array_filter($this->emp, fn($e) => ($e['ctype'] ?? 0) === 3 && !$e['retired']));
        foreach ($ps as $e) {
            foreach ([0, 1] as $n) {
                $start = $e['hire'];
                $val = $this->ri(8, 40) * 100000;
                $this->add('policies', [
                    'id' => $this->nid('policies'), 'uuid' => (string) Str::uuid(), 'model_type' => self::MH . 'Contract', 'model_id' => $e['contract'], 'risk_manager_id' => $this->pick(array_values($this->C['arl'])),
                    'policy_type_id' => $this->pick(array_values($this->C['policyType'])), 'code' => 'POL-' . $start->format('Y') . '-' . str_pad((string) $this->ri(1, 9999), 4, '0', STR_PAD_LEFT), 'value' => $val, 'insured_value' => $val * $this->ri(8, 20),
                    'start_date' => $this->dt($start), 'end_date' => $this->dt($start->addMonths(12)), 'status' => $start->addMonths(12) < $now ? $this->pick(['Vencida', 'Vigente']) : $this->pick(['Vigente', 'Vigente', 'En revisión']),
                    'created_at' => $this->ts($start), 'updated_at' => $this->ts($start),
                ]);
            }
        }
    }

    private function ins2(string $table, array $rows): void
    {
        foreach ($rows as $r) {
            $this->add($table, $r);
        }
    }

    private function addendum(int $contractId, string $typeName, array $f, string $mode, CarbonImmutable $created): void
    {
        $now = $this->now;
        $created = $created > $now ? $now->subDays(1) : $created;
        $tid = $this->C['addendumType'][$typeName];
        [$ps, $ls, $psAt, $lsAt] = match ($mode) {
            'ok' => ['Firmado', 'Firmado', $created->addDay(), $created->addDays(2)],
            'pending' => ['Pendiente', 'En revisión', null, null],
            'rep' => ['Firmado', 'Aprobado', $created->addDay(), null],
            'update' => ['Actualizar', 'En revisión', null, null],
            'withdrawn' => ['Desistió', 'En revisión', null, null],
            'rejected' => ['Pendiente', 'Rechazado', null, null],
        };
        $this->add('addendums', [
            'id' => $this->nid('addendums'), 'uuid' => (string) Str::uuid(), 'contract_id' => $contractId, 'addendum_type_id' => $tid, 'headquarter_id' => $f['headquarter_id'] ?? null, 'contract_type_id' => $f['contract_type_id'] ?? null,
            'position_id' => $f['position_id'] ?? null, 'salary' => $f['salary'] ?? null, 'payment_period_id' => null,
            'start_date' => isset($f['start_date']) ? $this->dt($f['start_date']) : null, 'end_date' => isset($f['end_date']) ? $this->dt($f['end_date']) : null,
            'person_observation' => $mode === 'update' ? 'El valor del salario no coincide con lo acordado.' : ($mode === 'withdrawn' ? 'Prefiero continuar en mi sede actual.' : null),
            'person_signed_at' => $psAt && $psAt <= $now ? $this->ts($psAt) : ($psAt ? $this->ts($now->subHours(2)) : null), 'person_status' => $ps,
            'legal_representative_signed_at' => $lsAt && $lsAt <= $now ? $this->ts($lsAt) : null, 'legal_representative_status' => $ls,
            'settings' => $this->j(['accept_terms' => $ps === 'Firmado', 'accept_privacy_policy' => $ps === 'Firmado', 'templates' => []]),
            'created_at' => $this->ts($created), 'updated_at' => $this->ts($created->addDays(2) > $now ? $now : $created->addDays(2)),
        ]);
    }

    // ------------------------------------------------------------ novedades
    private function hrEvents(): void
    {
        $now = $this->now;
        $active = array_values(array_filter($this->emp, fn($e) => !$e['retired']));
        $all = $this->emp;

        // Afiliaciones del personal sin aplicacion (todos aprobados)
        foreach ($this->emp as $e) {
            $this->affiliations($e['person'], 'ok', $e['hire']->subDays(2));
        }

        // Ausencias (permisos)
        $reasons = [
            'Cita médica' => ['Cita con médico general por control.', 'Cita con especialista asignada por la EPS.', 'Examen de laboratorio en ayunas.'],
            'Cita odontológica' => ['Control odontológico programado.', 'Procedimiento odontológico de urgencia.'],
            'Calamidad doméstica' => ['Inundación en la vivienda por daño de tubería.', 'Hospitalización de un familiar de primer grado.', 'Robo en la vivienda, debo realizar denuncia.'],
            'Diligencia personal' => ['Renovación de documentos en la Registraduría.', 'Trámite en la oficina de tránsito.', 'Reunión en el colegio de mi hijo.'],
            'Permiso de estudio' => ['Presentación de examen en la universidad.', 'Sustentación de trabajo de grado.'],
            'Trámite notarial o judicial' => ['Citación a audiencia de conciliación.', 'Firma de escritura en notaría.'],
        ];
        $comp = ['Se compensa con horas el sábado siguiente.', 'Descuento por nómina del tiempo no laborado.', 'Compensación con horas de tiempo extra.', null, null];
        for ($i = 0; $i < 72; $i++) {
            $e = $this->pick($active);
            $tn = $this->pick(array_keys($reasons));
            $start = $this->ago(1, 330);
            if ($start < $e['hire']->addDays(10)) {
                $start = $e['hire']->addDays($this->ri(15, 60));
            }
            if ($start > $now->addDays(14)) {
                $start = $now->subDays(3);
            }
            $start = $this->biz($start);
            $hours = $this->pick([2, 3, 4, 8, 8, 16, 24]);
            $st = $start > $now ? $this->weighted(['En revisión' => 80, 'Aprobado' => 20]) : ($start > $now->subDays(8) ? $this->weighted(['En revisión' => 50, 'Aprobado' => 40, 'Rechazado' => 10]) : $this->weighted(['Aprobado' => 78, 'Rechazado' => 22]));
            $this->add('absences', [
                'person_id' => $e['person']['id'], 'absence_type_id' => $this->C['absenceType'][$tn], 'start_date' => $this->ts($start), 'end_date' => $this->ts($start->addHours($hours)), 'reason' => $this->pick($reasons[$tn]),
                'compensation' => $this->pick($comp), 'status' => $st, 'created_at' => $this->ts($this->min($start->subDays($this->ri(0, 4)), $now)), 'updated_at' => $this->ts($this->min($start->subDays(1), $now)),
            ]);
        }

        // Vacaciones
        $vt = $this->C['vacationType'];
        $tenured = array_values(array_filter($active, fn($e) => $e['hire'] < $now->subMonths(13)));
        shuffle($tenured);
        $vc = 0;
        foreach (array_slice($tenured, 0, 30) as $e) {
            // periodo 2025 disfrutado
            $year = 2025;
            $days = $this->pick([15, 15, 15, 10, 8]);
            $start = CarbonImmutable::create(2025, $this->ri(1, 12), $this->ri(1, 20))->setTime(0, 0);
            if ($start < $e['hire']->addMonths(12)) {
                $start = $e['hire']->addMonths(12)->addDays($this->ri(5, 40));
            }
            if ($start > $now) {
                $start = $now->subMonths(2);
            }
            $this->addVac($e, $this->weighted([$vt['Vacaciones disfrutadas'] => 80, $vt['Vacaciones compensadas en dinero'] => 12, $vt['Vacaciones colectivas (cierre de fin de año)'] => 8]), $year, $days, $start, 'Aprobado');
            $vc++;
        }
        foreach (array_slice($tenured, 8, 14) as $e) {
            $days = $this->pick([5, 7, 8, 10, 15]);
            $future = $this->chance(0.55);
            $start = $future ? $now->addDays($this->ri(5, 80)) : $now->subDays($this->ri(10, 200));
            $st = $future ? $this->weighted(['En revisión' => 60, 'Aprobado' => 35, 'Rechazado' => 5]) : $this->weighted(['Aprobado' => 85, 'Rechazado' => 15]);
            $this->addVac($e, $vt['Vacaciones disfrutadas'], 2026, $days, $this->biz($start), $st);
        }

        // Incapacidades
        $codes = array_values($this->cie);
        $dtypes = $this->C['disabilityType'];
        $rs = ['Incapacidad médica por enfermedad general según concepto del médico tratante.', 'Reposo ordenado por urgencias.', 'Incapacidad posterior a procedimiento ambulatorio.', 'Cuadro respiratorio agudo con orden de reposo.', 'Dolor lumbar con restricción de actividad física.', 'Accidente en la planta durante la operación, atendido por la ARL.'];
        for ($i = 0; $i < 38; $i++) {
            $e = $this->pick($active);
            $start = $this->ago(2, 320);
            if ($start < $e['hire']->addDays(5)) {
                $start = $e['hire']->addDays($this->ri(10, 60));
            }
            $days = $this->weighted([2 => 25, 3 => 22, 5 => 18, 7 => 12, 10 => 8, 15 => 8, 30 => 5, 45 => 2]);
            $type = $this->weighted([$dtypes['Enfermedad general'] => 72, $dtypes['Accidente de trabajo'] => 14, $dtypes['Enfermedad laboral'] => 8, $dtypes['Accidente de tránsito'] => 6]);
            $end = $start->addDays($days - 1);
            $st = $start > $now->subDays(6) ? $this->weighted(['En revisión' => 75, 'Aprobado' => 25]) : $this->weighted(['Aprobado' => 84, 'Rechazado' => 8, 'En revisión' => 8]);
            $this->add('disabilities', [
                'person_id' => $e['person']['id'], 'disability_type_id' => $type, 'cie_code_id' => $this->pick($codes), 'start_date' => $this->dt($start), 'end_date' => $this->dt($end),
                'reason' => $this->pick($rs), 'status' => $st, 'created_at' => $this->ts($this->min($start->addHours(10), $now)), 'updated_at' => $this->ts($this->min($end->addDays(1), $now)),
            ]);
        }

        // Licencias
        $lt = $this->C['licenseType'];
        $licenses = [
            ['Licencia de maternidad', 126, 'Licencia de maternidad por nacimiento.'], ['Licencia de maternidad', 126, 'Licencia de maternidad.'],
            ['Licencia de paternidad', 14, 'Licencia de paternidad por nacimiento de mi hijo.'], ['Licencia de paternidad', 14, 'Licencia de paternidad.'],
            ['Licencia por luto', 5, 'Fallecimiento de un familiar de primer grado.'], ['Licencia por luto', 5, 'Fallecimiento de mi padre.'], ['Licencia por luto', 5, 'Fallecimiento de mi abuela.'], ['Licencia por luto', 5, 'Fallecimiento de un familiar.'],
            ['Licencia no remunerada', 30, 'Viaje familiar fuera del país.'], ['Licencia no remunerada', 15, 'Asuntos personales de fuerza mayor.'], ['Licencia no remunerada', 20, 'Trámite de visa y calamidad familiar.'],
            ['Licencia por matrimonio', 5, 'Contraje matrimonio civil.'], ['Licencia de maternidad', 126, 'Licencia de maternidad por parto.'], ['Licencia de paternidad', 14, 'Nacimiento de mi hija.'], ['Licencia no remunerada', 10, 'Estudio de idiomas.'],
        ];
        foreach ($licenses as [$tn, $days, $reason]) {
            $pool = $tn === 'Licencia de maternidad' ? array_values(array_filter($active, fn($e) => $e['person']['sex'] === 'F')) : ($tn === 'Licencia de paternidad' ? array_values(array_filter($active, fn($e) => $e['person']['sex'] === 'M')) : $active);
            $e = $this->pick($pool);
            $start = $tn === 'Licencia de maternidad' ? $this->ago(20, 110) : $this->ago(3, 280);
            if ($start < $e['hire']->addDays(30)) {
                $start = $e['hire']->addDays($this->ri(40, 100));
            }
            $st = $start > $now->subDays(5) ? 'En revisión' : $this->weighted(['Aprobado' => 85, 'Rechazado' => 8, 'En revisión' => 7]);
            $this->add('licenses', ['person_id' => $e['person']['id'], 'license_type_id' => $lt[$tn], 'start_date' => $this->dt($start), 'end_date' => $this->dt($start->addDays($days - 1)), 'reason' => $reason, 'status' => $st, 'created_at' => $this->ts($this->min($start->subDays(3), $now)), 'updated_at' => $this->ts($this->min($start, $now))]);
        }

        // Cesantias
        $sv = $this->C['severanceType'];
        $svr = [
            'Cesantías parciales para vivienda' => ['Compra de vivienda con crédito hipotecario.', 'Mejoras locativas de vivienda propia.', 'Abono a crédito de vivienda.'],
            'Cesantías parciales para educación' => ['Matrícula universitaria del semestre.', 'Pago de matrícula de mi hijo.', 'Curso de formación técnica.'],
            'Cesantías definitivas por retiro' => ['Retiro definitivo por terminación de contrato.'],
        ];
        for ($i = 0; $i < 14; $i++) {
            $e = $this->pick($tenured);
            $tn = $this->weighted(['Cesantías parciales para vivienda' => 40, 'Cesantías parciales para educación' => 45, 'Cesantías definitivas por retiro' => 15]);
            $at = $this->ago(3, 280);
            $st = $at > $now->subDays(10) ? 'En revisión' : $this->weighted(['Aprobado' => 78, 'Rechazado' => 10, 'En revisión' => 12]);
            $this->add('severances', ['person_id' => $e['person']['id'], 'severance_type_id' => $sv[$tn], 'reason' => $this->pick($svr[$tn]), 'status' => $st, 'created_at' => $this->ts($at), 'updated_at' => $this->ts($this->min($at->addDays(3), $now))]);
        }

        // Retiros: renuncias y terminaciones
        $rt = $this->C['resignationType'];
        $tt = $this->C['terminationType'];
        $retired = array_values(array_filter($this->emp, fn($e) => $e['retired']));
        foreach ($retired as $n => $e) {
            if ($n % 2 === 0) {
                $this->add('resignations', ['person_id' => $e['person']['id'], 'resignation_type_id' => $this->weighted([$rt['Renuncia voluntaria'] => 55, $rt['Renuncia por oferta laboral'] => 30, $rt['Renuncia por mutuo acuerdo'] => 15]), 'date' => $this->dt($e['end']), 'reason' => $this->pick(['Mejor oferta laboral cerca de mi residencia.', 'Motivos personales y cambio de ciudad.', 'Voy a iniciar un emprendimiento propio.', 'Razones familiares.', 'Busco una oportunidad de crecimiento profesional.']), 'status' => 'Aprobado', 'created_at' => $this->ts($e['end']->subDays($this->ri(15, 30))), 'updated_at' => $this->ts($e['end']->subDays(10))]);
            } else {
                $dec = $this->pick(['Terminar', 'No renovar']);
                $this->add('terminations', ['person_id' => $e['person']['id'], 'model_type' => self::MH . 'Contract', 'model_id' => $e['contract'], 'reason' => $dec === 'No renovar' ? 'No se renueva el contrato por bajo rendimiento sostenido y llamados de atención.' : 'Terminación del contrato por decisión de la empresa por reestructuración del módulo de producción.', 'date' => $this->dt($e['end']), 'decision' => $dec, 'status' => 'Aprobado', 'created_at' => $this->ts($e['end']->subDays($this->ri(20, 40))), 'updated_at' => $this->ts($e['end']->subDays(12))]);
            }
        }
        // renuncias pendientes
        foreach (array_slice($active, 3, 1) as $e) {
            $this->add('resignations', ['person_id' => $e['person']['id'], 'resignation_type_id' => $rt['Renuncia por oferta laboral'], 'date' => $this->dt($now->addDays(20)), 'reason' => 'Recibí una oferta laboral en otra ciudad y debo trasladarme.', 'status' => 'En revisión', 'created_at' => $this->ts($now->subDays(2)), 'updated_at' => $this->ts($now->subDays(2))]);
        }
        $e = $active[7];
        $this->add('resignations', ['person_id' => $e['person']['id'], 'resignation_type_id' => $rt['Renuncia voluntaria'], 'date' => $this->dt($now->subDays(15)), 'reason' => 'Motivos personales.', 'status' => 'Rechazado', 'created_at' => $this->ts($now->subDays(30)), 'updated_at' => $this->ts($now->subDays(25))]);
        $e = $active[9];
        $this->add('resignations', ['person_id' => $e['person']['id'], 'resignation_type_id' => $rt['Renuncia por mutuo acuerdo'], 'date' => $this->dt($now->addDays(30)), 'reason' => 'Acuerdo con la empresa para terminar relación laboral al cierre del mes.', 'status' => 'En revisión', 'created_at' => $this->ts($now->subDays(1)), 'updated_at' => $this->ts($now->subDays(1))]);

        // Decisiones de terminacion para contratos a termino fijo proximos a vencer
        $soon = array_values(array_filter($this->emp, fn($e) => !$e['retired'] && ($e['ctype'] ?? 0) === 1 && ($e['cend'] ?? null) && $e['cend'] > $now && $e['cend'] < $now->addDays(70)));
        foreach (array_slice($soon, 0, 6) as $n => $e) {
            $this->add('terminations', ['person_id' => $e['person']['id'], 'model_type' => self::MH . 'Contract', 'model_id' => $e['contract'], 'reason' => $n % 3 === 0 ? 'Rendimiento por debajo de la meta en los últimos tres meses.' : 'Cumplimiento de metas y buen desempeño; se propone renovación.', 'date' => $this->dt($e['cend']), 'decision' => $n % 3 === 0 ? 'No renovar' : 'Renovar', 'status' => $n < 4 ? 'En revisión' : 'Aprobado', 'created_at' => $this->ts($now->subDays($this->ri(1, 15))), 'updated_at' => $this->ts($now->subDays(1))]);
        }
        // Terminaciones por fin de contrato de aplicaciones desistidas no aplican
    }

    private function addVac(array $e, int $typeId, int $year, int $days, CarbonImmutable $start, string $st): void
    {
        $this->add('vacations', [
            'person_id' => $e['person']['id'], 'vacation_type_id' => $typeId, 'period' => $year, 'days' => $days, 'start_date' => $this->dt($start), 'end_date' => $this->dt($start->addDays($days + 1)),
            'status' => $st, 'created_at' => $this->ts($this->min($start->subDays($this->ri(10, 30)), $this->now)), 'updated_at' => $this->ts($this->min($start->subDays($this->ri(1, 8)), $this->now)),
        ]);
    }

    // ------------------------------------------------------------ inducciones y encuestas
    private function inductionsAndSurveys(): void
    {
        $now = $this->now;
        $recent = array_values(array_filter($this->emp, fn($e) => !$e['retired'] && $e['hire'] > $now->subMonths(12)));
        foreach ($recent as $e) {
            $d = $this->pos[$e['pos']];
            $sets = [$this->induction['corp']];
            if ($d['area'] === 'Producción') {
                $sets[] = $this->induction['prod'];
            } elseif ($d['area'] === 'Comercial y Ventas') {
                $sets[] = $this->induction['com'];
            }
            $daysSince = (int) $e['hire']->diffInDays($now);
            $t = $e['hire']->addDays(1)->setTime(8, 30);
            $stepNo = 0;
            foreach ($sets as $steps) {
                foreach ($steps as $s) {
                    $form = $this->forms[$s['form']];
                    $start = $t->addDays($stepNo);
                    $done = $start->addHours($this->ri(1, 4));
                    $stepNo++;
                    if ($start > $now) {
                        break 2;
                    }
                    $finished = $done <= $now && ($daysSince > 14 || $this->chance(0.5));
                    $answers = ['leido' => true, 'q1' => $this->chance(0.85) ? 'Respuesta correcta' : 'Respuesta parcial', 'dudas' => $this->chance(0.3) ? 'Ninguna por el momento.' : ''];
                    $this->add('progress', [
                        'person_id' => $e['person']['id'], 'step_id' => $s['id'], 'questions' => $this->j($form['q']), 'answers' => $this->j($finished ? $answers : new \stdClass()), 'start_at' => $this->ts($start),
                        'end_at' => $finished ? $this->ts($done) : null, 'status' => $finished ? 'Finalizado' : 'En curso', 'result' => $finished ? ($answers['q1'] === 'Respuesta correcta' ? 'Aprobado' : 'Pendiente') : 'Pendiente',
                        'created_at' => $this->ts($start), 'updated_at' => $this->ts($finished ? $done : $start),
                    ]);
                }
            }
        }

        // Encuesta de clima
        $active = array_values(array_filter($this->emp, fn($e) => !$e['retired']));
        shuffle($active);
        foreach (array_slice($active, 0, 34) as $e) {
            $form = $this->forms['clima'];
            $at = $this->ago(3, 60);
            $this->add('surveys', ['form_id' => $form['id'], 'questions' => $this->j($form['q']), 'answers' => $this->j($this->answers($form['q'])), 'person_id' => $e['person']['id'], 'created_at' => $this->ts($at), 'updated_at' => $this->ts($at)]);
        }
        // Calificacion de la empresa por candidatos que terminaron proceso
        $done = array_values(array_filter($this->apps, fn($a) => in_array($a['status'], ['Rechazado', 'Desistió', 'Vinculado'])));
        shuffle($done);
        $seen = [];
        foreach ($done as $a) {
            if (isset($seen[$a['person']['id']])) {
                continue;
            }
            $seen[$a['person']['id']] = 1;
            if (count($seen) > 28) {
                break;
            }
            $form = $this->forms['rating'];
            $at = $this->min($a['created']->addDays($this->ri(10, 40)), $now->subDays(1));
            $this->add('surveys', ['form_id' => $form['id'], 'questions' => $this->j($form['q']), 'answers' => $this->j($this->answers($form['q'])), 'person_id' => $a['person']['id'], 'created_at' => $this->ts($at), 'updated_at' => $this->ts($at)]);
        }
    }
}
