<?php

namespace Database\Seeders;

use App\Models\AbsenceType;
use App\Models\AcademicLevel;
use App\Models\AddendumType;
use App\Models\Area;
use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\Benefit;
use App\Models\BloodType;
use App\Models\CertificateType;
use App\Models\CIEChapter;
use App\Models\CIECode;
use App\Models\CIEGroup;
use App\Models\CIESubgroup;
use App\Models\CompensationFund;
use App\Models\ContractType;
use App\Models\DisabilityType;
use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\Entity;
use App\Models\EntityType;
use App\Models\EpidemiologicalProgram;
use App\Models\FileSubtype;
use App\Models\FileType;
use App\Models\FiscalDocument;
use App\Models\FormationStatus;
use App\Models\FormType;
use App\Models\Gender;
use App\Models\Headquarter;
use App\Models\HealthEntity;
use App\Models\HealthEntityType;
use App\Models\HiringReason;
use App\Models\Item;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\LicenseType;
use App\Models\MaritalStatus;
use App\Models\NotificationChannel;
use App\Models\NotificationPlatform;
use App\Models\NotificationType;
use App\Models\PaymentPeriod;
use App\Models\PensionFund;
use App\Models\PersonType;
use App\Models\PolicyType;
use App\Models\Position;
use App\Models\ProfileCategory;
use App\Models\Reason;
use App\Models\Relationship;
use App\Models\ResignationType;
use App\Models\RiskManager;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\SeveranceType;
use App\Models\Sex;
use App\Models\Size;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\Subitem;
use App\Models\TerminationType;
use App\Models\VacationType;
use App\Models\WorkArrangement;
use App\Models\WorkDay;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use OpenApi\Attributes\Head;

class ItemsAndSubitemsSeeder extends Seeder
{
    public function run(): void
    {
        $item = new Item();
        $item->name = 'Tipo de Contrato';
        $item->description = 'Listado de tipos de contrato disponibles.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Término Fijo', 'description' => 'Contrato con fecha de finalización establecida.'],
            (object) ['name' => 'Término Indefinido', 'description' => 'Contrato sin fecha de terminación definida.'],
            (object) ['name' => 'Prestación de Servicios', 'description' => 'Contrato por honorarios para servicios específicos.'],
            (object) ['name' => 'Aprendizaje', 'description' => 'Contrato para formación práctica de estudiantes.'],
            (object) ['name' => 'Obra o Labor', 'description' => 'Contrato asociado a la realización de una obra o actividad específica.']
        ];

        foreach ($subitems as $subitem) {
            $contractType = new ContractType();
            $contractType->name = $subitem->name;
            $contractType->description = $subitem->description;
            $contractType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Jornada Laboral';
        $item->description = 'Tipos de jornada laboral.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Jornada Completa', 'description' => 'Trabajo a tiempo completo.'],
            (object) ['name' => 'Medio Tiempo', 'description' => 'Trabajo a medio tiempo.'],
            (object) ['name' => 'Turnos Rotativos', 'description' => 'Trabajo con turnos rotativos.']
        ];

        foreach ($subitems as $subitem) {
            $workDay = new WorkDay();
            $workDay->name = $subitem->name;
            $workDay->description = $subitem->description;
            $workDay->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Modalidad de Trabajo';
        $item->description = 'Modalidades de trabajo disponibles.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Presencial', 'description' => 'Trabajo realizado en oficina o sitio físico.'],
            (object) ['name' => 'Virtual', 'description' => 'Trabajo realizado de manera remota.'],
            (object) ['name' => 'Híbrido', 'description' => 'Trabajo combinado entre presencial y virtual.']
        ];

        foreach ($subitems as $subitem) {
            $workArrangement = new WorkArrangement();
            $workArrangement->name = $subitem->name;
            $workArrangement->description = $subitem->description;
            $workArrangement->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipo de Documento';
        $item->description = 'Tipos de documento válidos.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Cédula de Ciudadanía',
                'description' => 'Documento para ciudadanos.',
                'settings' => (object) [
                    'abreviature' => 'CC',
                    'validations' => [
                        (object) [
                            'id' => 1,
                            'country_id' => 48,
                            'regex' => '/^[0-9]{6,10}$/',
                            'message' => 'Debe tener entre 6 y 10 dígitos numéricos.',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'deleted_at' => null
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Cédula de Extranjería',
                'description' => 'Documento para extranjeros residentes.',
                'settings' => (object) [
                    'abreviature' => 'CE',
                    'validations' => [
                        (object) [
                            'id' => 1,
                            'country_id' => 48,
                            'regex' => '/^[A-Z0-9]{5,12}$/',
                            'message' => 'Debe ser alfanumérico de 5 a 12 caracteres.',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'deleted_at' => null
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Pasaporte',
                'description' => 'Documento internacional de identificación.',
                'settings' => (object) [
                    'abreviature' => 'PA',
                    'validations' => [
                        (object) [
                            'id' => 1,
                            'country_id' => 48,
                            'regex' => '/^[A-Z]{2}[0-9]{6}$/',
                            'message' => 'Debe tener 2 letras seguidas de 6 números.',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'deleted_at' => null
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Tarjeta de Identidad',
                'description' => 'Documento para menores de edad.',
                'settings' => (object) [
                    'abreviature' => 'TI',
                    'validations' => [
                        (object) [
                            'id' => 1,
                            'country_id' => 48,
                            'regex' => '/^[0-9]{6,8}$/',
                            'message' => 'Debe tener entre 6 y 8 dígitos numéricos.',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'deleted_at' => null
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Permiso Especial de Permanencia',
                'description' => 'Documento especial para extranjeros.',
                'settings' => (object) [
                    'abreviature' => 'PEP',
                    'validations' => [
                        (object) [
                            'id' => 1,
                            'country_id' => 48,
                            'regex' => '/^[A-Z0-9]{6,12}$/',
                            'message' => 'Debe ser alfanumérico de 6 a 12 caracteres.',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'deleted_at' => null
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Permiso por Protección Temporal',
                'description' => 'Documento temporal para protección especial de extranjeros.',
                'settings' => (object) [
                    'abreviature' => 'PPT',
                    'validations' => [
                        (object) [
                            'id' => 1,
                            'country_id' => 48,
                            'regex' => '/^[A-Z0-9]{6,12}$/',
                            'message' => 'Debe ser alfanumérico de 6 a 12 caracteres.',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'deleted_at' => null
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'NIT',
                'description' => 'Número de Identificación Tributaria.',
                'settings' => (object) [
                    'abreviature' => 'NIT',
                    'validations' => [
                        (object) [
                            'id' => 1,
                            'country_id' => 48,
                            'regex' => '/^[0-9]{9,15}-[0-9]$/',
                            'message' => 'Debe tener entre 9 y 15 dígitos numéricos seguidos de un guion y un dígito identificador.',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'deleted_at' => null
                        ]
                    ]
                ]
            ]
        ];

        foreach ($subitems as $subitem) {
            $documentType = new DocumentType();
            $documentType->name = $subitem->name;
            $documentType->description = $subitem->description;
            $documentType->settings = $subitem->settings;
            $documentType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipo de Persona';
        $item->description = 'Clasificación que identifica la naturaleza jurídica de una persona dentro del sistema.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Natural', 'description' => 'Persona natural.', 'document_types' => [12, 13, 14, 15, 16, 17]],
            (object) ['name' => 'Jurídica', 'description' => 'Persona jurídica.', 'document_types' => [18]]
        ];

        foreach ($subitems as $subitem) {
            $personType = new PersonType();
            $personType->name = $subitem->name;
            $personType->description = $subitem->description;
            $personType->save();

            $personType->document_types()->sync($subitem->document_types);
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipo de Habilidades';
        $item->description = 'Clasificación de habilidades.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Blanda',
                'description' => 'Habilidades sociales y actitudinales.',
                'settings' => (object) [
                    'regex' => '/^[1-5]$/',
                    'message' => 'Seleccione una calificación de 1 a 5 estrellas.'
                ]
            ],
            (object) [
                'name' => 'Dura',
                'description' => 'Habilidades técnicas y específicas.',
                'settings' => (object) [
                    'regex' => '/^(100|[1-9][0-9]?|0?[1-9])$/',
                    'message' => 'Ingrese solo números enteros entre 1 y 100.'
                ]
            ]
        ];

        foreach ($subitems as $subitem) {
            $skillType = new SkillType();
            $skillType->name = $subitem->name;
            $skillType->description = $subitem->description;
            $skillType->settings = $subitem->settings;
            $skillType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Estado de la formación';
        $item->description = 'Estados que describen la situación de la formación académica.';
        $item->save();

        $subitems = [
            (object) ['name' => 'No Iniciado', 'description' => 'La formación aún no ha comenzado.'],
            (object) ['name' => 'En Curso', 'description' => 'La formación está actualmente en desarrollo.'],
            (object) ['name' => 'Suspendido', 'description' => 'La formación está temporalmente suspendida.'],
            (object) ['name' => 'Abandonado', 'description' => 'La formación fue abandonada antes de finalizar.'],
            (object) ['name' => 'Finalizado', 'description' => 'La formación fue completada satisfactoriamente.']
        ];

        foreach ($subitems as $subitem) {
            $formationStatus = new FormationStatus();
            $formationStatus->name = $subitem->name;
            $formationStatus->description = $subitem->description;
            $formationStatus->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Nivel Académico';
        $item->description = 'Grados de formación académica.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Básica Primaria', 'description' => 'Primera etapa de formación escolar.'],
            (object) ['name' => 'Básica Secundaria', 'description' => 'Formación escolar secundaria.'],
            (object) ['name' => 'Media', 'description' => 'Educación media (bachillerato).'],
            (object) ['name' => 'Técnico', 'description' => 'Formación técnica.'],
            (object) ['name' => 'Tecnólogo', 'description' => 'Formación tecnológica.'],
            (object) ['name' => 'Profesional', 'description' => 'Formación universitaria.'],
            (object) ['name' => 'Especialización', 'description' => 'Estudios de especialización.'],
            (object) ['name' => 'Maestría', 'description' => 'Estudios de maestría.'],
            (object) ['name' => 'Doctorado', 'description' => 'Estudios de doctorado.'],
            (object) ['name' => 'Curso', 'description' => 'Estudios cortos y específicos sobre una temática.'],
            (object) ['name' => 'Diplomado', 'description' => 'Programa académico de corta duración sobre un área específica.'],
            (object) ['name' => 'Certificación', 'description' => 'Reconocimiento formal de competencias adquiridas.'],
            (object) ['name' => 'Seminario', 'description' => 'Evento académico breve sobre temas concretos.'],
            (object) ['name' => 'Taller', 'description' => 'Espacio práctico de aprendizaje en un área específica.'],
        ];

        foreach ($subitems as $subitem) {
            $academicLevel = new AcademicLevel();
            $academicLevel->name = $subitem->name;
            $academicLevel->description = $subitem->description;
            $academicLevel->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Sexo';
        $item->description = 'Sexo biológico.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Masculino', 'description' => 'Sexo masculino.'],
            (object) ['name' => 'Femenino', 'description' => 'Sexo femenino.']
        ];

        foreach ($subitems as $subitem) {
            $sex = new Sex();
            $sex->name = $subitem->name;
            $sex->description = $subitem->description;
            $sex->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Género';
        $item->description = 'Identidad de género.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Hombre', 'description' => 'Identidad masculina.'],
            (object) ['name' => 'Mujer', 'description' => 'Identidad femenina.'],
            (object) ['name' => 'No Binario', 'description' => 'No se identifica como hombre ni mujer.'],
            (object) ['name' => 'Prefiere no decirlo', 'description' => 'Prefiere no especificar.'],
            (object) ['name' => 'Otro', 'description' => 'Otra identidad de género.']
        ];

        foreach ($subitems as $subitem) {
            $gender = new Gender();
            $gender->name = $subitem->name;
            $gender->description = $subitem->description;
            $gender->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipo de Sangre';
        $item->description = 'Tipos de sangre y factores Rh.';
        $item->save();

        $subitems = [
            (object) ['name' => 'A+', 'description' => 'Tipo de sangre A positivo.'],
            (object) ['name' => 'A-', 'description' => 'Tipo de sangre A negativo.'],
            (object) ['name' => 'B+', 'description' => 'Tipo de sangre B positivo.'],
            (object) ['name' => 'B-', 'description' => 'Tipo de sangre B negativo.'],
            (object) ['name' => 'AB+', 'description' => 'Tipo de sangre AB positivo.'],
            (object) ['name' => 'AB-', 'description' => 'Tipo de sangre AB negativo.'],
            (object) ['name' => 'O+', 'description' => 'Tipo de sangre O positivo.'],
            (object) ['name' => 'O-', 'description' => 'Tipo de sangre O negativo.']
        ];

        foreach ($subitems as $subitem) {
            $bloodType = new BloodType();
            $bloodType->name = $subitem->name;
            $bloodType->description = $subitem->description;
            $bloodType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Estado Civil';
        $item->description = 'Estados civiles reconocidos.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Soltero', 'description' => 'Persona sin vínculo matrimonial.'],
            (object) ['name' => 'Casado', 'description' => 'Persona unida en matrimonio.'],
            (object) ['name' => 'Unión Libre', 'description' => 'Persona en unión marital de hecho.'],
            (object) ['name' => 'Divorciado', 'description' => 'Persona con matrimonio disuelto legalmente.'],
            (object) ['name' => 'Viudo', 'description' => 'Persona cuyo cónyuge ha fallecido.']
        ];

        foreach ($subitems as $subitem) {
            $maritalStatus = new MaritalStatus();
            $maritalStatus->name = $subitem->name;
            $maritalStatus->description = $subitem->description;
            $maritalStatus->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Categoría Perfil';
        $item->description = 'Clasificación del perfil laboral.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Operativo', 'description' => 'Perfil operativo.'],
            (object) ['name' => 'Administrativo', 'description' => 'Perfil administrativo.']
        ];

        foreach ($subitems as $subitem) {
            $profileCategory = new ProfileCategory();
            $profileCategory->name = $subitem->name;
            $profileCategory->description = $subitem->description;
            $profileCategory->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Bancos';
        $item->description = 'Entidades bancarias.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Banco de Bogotá', 'description' => 'Banco de Bogotá.', 'countries' => [48]],
            (object) ['name' => 'Banco Popular', 'description' => 'Banco Popular.', 'countries' => [48]],
            (object) ['name' => 'Itaú', 'description' => 'Itaú.', 'countries' => [48]],
            (object) ['name' => 'Bancolombia', 'description' => 'Bancolombia.', 'countries' => [48]],
            (object) ['name' => 'Citibank', 'description' => 'Citibank.', 'countries' => [48]],
            (object) ['name' => 'Banco GNB Sudameris', 'description' => 'Banco GNB Sudameris.', 'countries' => [48]],
            (object) ['name' => 'BBVA', 'description' => 'BBVA.', 'countries' => [48]],
            (object) ['name' => 'Banco de Occidente', 'description' => 'Banco de Occidente.', 'countries' => [48]],
            (object) ['name' => 'Banco Caja Social', 'description' => 'Banco Caja Social.', 'countries' => [48]],
            (object) ['name' => 'Banco Davivienda', 'description' => 'Banco Davivienda.', 'countries' => [48]],
            (object) ['name' => 'Scotiabank Colpatria', 'description' => 'Scotiabank Colpatria.', 'countries' => [48]],
            (object) ['name' => 'Banco Agrario de Colombia', 'description' => 'Banco Agrario de Colombia.', 'countries' => [48]],
            (object) ['name' => 'Banco AV Villas', 'description' => 'Banco AV Villas.', 'countries' => [48]],
            (object) ['name' => 'Bancien', 'description' => 'Bancien o BAN100.', 'countries' => [48]],
            (object) ['name' => 'Bancamía', 'description' => 'Banco de las Microfinanzas Bancamía.', 'countries' => [48]],
            (object) ['name' => 'Banco W', 'description' => 'Banco W.', 'countries' => [48]],
            (object) ['name' => 'Banco Coomeva', 'description' => 'Banco Coomeva.', 'countries' => [48]],
            (object) ['name' => 'Banco Finandina', 'description' => 'Banco Finandina.', 'countries' => [48]],
            (object) ['name' => 'Banco Falabella', 'description' => 'Banco Falabella.', 'countries' => [48]],
            (object) ['name' => 'Banco Pichincha', 'description' => 'Banco Pichincha.', 'countries' => [48]],
            (object) ['name' => 'Coopcentral', 'description' => 'Banco Cooperativo Coopcentral.', 'countries' => [48]],
            (object) ['name' => 'Banco Santander', 'description' => 'Banco Santander de Negocios Colombia.', 'countries' => [48]],
            (object) ['name' => 'Banco Mundo Mujer', 'description' => 'Banco Mundo Mujer.', 'countries' => [48]],
            (object) ['name' => 'Mibanco', 'description' => 'Banco de la Microempresa Mibanco.', 'countries' => [48]],
            (object) ['name' => 'Banco Serfinanza', 'description' => 'Banco Serfinanza.', 'countries' => [48]],
            (object) ['name' => 'Banco J.P. Morgan', 'description' => 'Banco J.P. Morgan Colombia.', 'countries' => [48]],
            (object) ['name' => 'Lulo Bank', 'description' => 'Lulo Bank.', 'countries' => [48]],
            (object) ['name' => 'Banco BTG Pactual', 'description' => 'Banco BTG Pactual Colombia.', 'countries' => [48]],
            (object) ['name' => 'Banco Unión', 'description' => 'Banco Unión.', 'countries' => [48]],
            (object) ['name' => 'Banco Contactar', 'description' => 'Banco Contactar.', 'countries' => [48]]
        ];

        foreach ($subitems as $subitem) {
            $bank = new Bank();
            $bank->name = $subitem->name;
            $bank->description = $subitem->description;
            $bank->save();

            $bank->countries()->sync($subitem->countries);
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipo de Cuenta Bancaria';
        $item->description = 'Tipos de cuenta bancaria disponibles.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Ahorros', 'description' => 'Cuenta de ahorros.'],
            (object) ['name' => 'Corriente', 'description' => 'Cuenta corriente.']
        ];

        foreach ($subitems as $subitem) {
            $bankAccountType = new BankAccountType();
            $bankAccountType->name = $subitem->name;
            $bankAccountType->description = $subitem->description;
            $bankAccountType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Archivos';
        $item->description = 'Clasificación estandarizada de archivos según su naturaleza, uso y propósito dentro de procesos administrativos, legales o documentales.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Identificación',
                'description' => 'Documentos que validan la identidad de personas o entidades (cédula, pasaporte, RUT, cámara de comercio, etc.).',
                'file_subtypes' => [102, 103, 104, 105]
            ],
            (object) [
                'name' => 'Imagen Corporativa',
                'description' => 'Elementos visuales que representan a la organización (logos, manual de marca, tarjetas, firmas digitales, plantillas gráficas).',
                'file_subtypes' => [106, 107, 108, 109, 110, 111]
            ],
            (object) [
                'name' => 'Plantillas Contractuales',
                'description' => 'Modelos predefinidos de documentos legales y de vinculación usados en procesos corporativos.',
                'file_subtypes' => [112, 113, 114, 115, 116, 117, 118, 119, 120, 131, 132, 133]
            ],
            (object) [
                'name' => 'Documentos Académicos o Laborales',
                'description' => 'Títulos, certificados de cursos, certificaciones técnicas, evidencia de competencias.',
                'file_subtypes' => [121, 122, 123]
            ],
            (object) [
                'name' => 'Documentos Personales',
                'description' => 'Documentos que identifican o describen información personal, historial o soporte individual.',
                'file_subtypes' => [124, 125, 129, 130]
            ],
            (object) [
                'name' => 'Producción de Contenido',
                'description' => 'Archivos multimedia o escritos generados por el usuario (videos, imágenes, audios).',
                'file_subtypes' => [126, 127, 128]
            ]
        ];

        foreach ($subitems as $subitem) {
            $fileType = new FileType();
            $fileType->name = $subitem->name;
            $fileType->description = $subitem->description;
            $fileType->save();

            $fileType->file_subtypes()->sync($subitem->file_subtypes);
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Subtipos de Archivos';
        $item->description = 'Clasificación específica de archivos según su contenido y función.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Documento de Identidad', 'description' => 'Cédula, pasaporte, licencia u otro documento identificador.'],
            (object) ['name' => 'Identificación Tributaria o Fiscal', 'description' => 'Número o certificado oficial usado para fines tributarios (ej. NIT, VAT, RFC, CUIT).'],
            (object) ['name' => 'Cámara de Comercio', 'description' => 'Certificado de existencia y representación legal de una empresa.'],
            (object) ['name' => 'Poder o Representación Legal', 'description' => 'Documento que acredita facultades legales sobre una entidad.'],
            (object) ['name' => 'Logo', 'description' => 'Archivo del logotipo institucional en formatos editables o exportados.'],
            (object) ['name' => 'Imagen Banner', 'description' => 'Imagen institucional usada en web, redes o presentaciones.'],
            (object) ['name' => 'Imagen Misión', 'description' => 'Gráfico que representa la razón de ser de la organización.'],
            (object) ['name' => 'Imagen Visión', 'description' => 'Gráfico que representa la proyección o meta futura de la organización.'],
            (object) ['name' => 'Imagen Sobre Nosotros', 'description' => 'Composición visual que representa presentación institucional.'],
            (object) ['name' => 'Imagen de Valores Corporativos', 'description' => 'Diseños que ilustran declaraciones de valores organizacionales.'],
            (object) [
                'name' => 'Plantilla de Contratación Directa',
                'description' => 'Modelo base para vinculación formal bajo relación laboral.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Empleador',
                            'path'  => 'contract',
                            'groups' => [
                                [
                                    'title' => 'Empresa',
                                    'path'  => 'application.vacant.headquarter.entity.enterprise',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field'  => 'name', 'key' => ':enterprise_name'],
                                        ['label' => 'Descripcion', 'field'  => 'description', 'key' => ':enterprise_description']
                                    ]
                                ],
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'application.vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_entity_type'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Representante legal',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'application.vacant.headquarter.entity.person.first_name', 'key' => ':legal_representative_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'application.vacant.headquarter.entity.person.second_name', 'key' => ':legal_representative_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'application.vacant.headquarter.entity.person.first_last_name', 'key' => ':legal_representative_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'application.vacant.headquarter.entity.person.second_last_name', 'key' => ':legal_representative_second_last_name'],
                                        ['label' => 'Tipo documento', 'field' => 'application.vacant.headquarter.entity.person.document_type.settings.abreviature', 'key' => ':legal_representative_document_type'],
                                        ['label' => 'Documento', 'field' => 'application.vacant.headquarter.entity.person.document_number', 'key' => ':legal_representative_document_number'],
                                        ['label' => 'Firma', 'field' => 'legal_representative_signature.path', 'key' => ':legal_representative_signature', 'type' => 'file']
                                    ]
                                ],
                                [
                                    'title' => 'Sede',
                                    'path'  => 'application.vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'Departamento', 'field' => 'city.department.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad',  'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address'],
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Candidato',
                            'path'  => 'contract',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'person.first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'person.second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'person.first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'person.second_last_name', 'key' => ':person_second_last_name'],
                                        ['label' => 'Firma', 'field' => 'person_signature.path', 'key' => ':person_signature', 'type' => 'file']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Contrato',
                            'path'  => 'contract',
                            'groups' => [
                                [
                                    'title' => 'Información relevante',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Cargo', 'field' => 'position.name', 'key' => ':contract_position'],
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_contract_type'],
                                        ['label' => 'Periodo de pago', 'field' => 'payment_period.name', 'key' => ':contract_payment_period'],
                                        ['label' => 'Salario', 'field' => 'salary', 'key' => ':contract_salary'],
                                        ['label' => 'Auxilio de transporte', 'field' => 'transport_allowance', 'key' => ':contract_transport_allowance'],
                                        ['label' => 'Fecha inicio labores', 'field' => 'start_date', 'key' => ':contract_start_date'],
                                        ['label' => 'Fecha fin labores', 'field' => 'end_date', 'key' => ':contract_end_date']
                                    ]
                                ]
                            ]
                        ],
                    ]
                ]
            ],
            (object) [
                'name' => 'Plantilla de Vinculación por Prestación de Servicios',
                'description' => 'Modelo para acuerdos con contratistas externos sin relación laboral.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Empleador',
                            'path'  => 'contract',
                            'groups' => [
                                [
                                    'title' => 'Empresa',
                                    'path'  => 'application.vacant.headquarter.entity.enterprise',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field'  => 'name', 'key' => ':enterprise_name'],
                                        ['label' => 'Descripcion', 'field'  => 'description', 'key' => ':enterprise_description']
                                    ]
                                ],
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'application.vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_entity_type'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Representante legal',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'application.vacant.headquarter.entity.person.first_name', 'key' => ':legal_representative_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'application.vacant.headquarter.entity.person.second_name', 'key' => ':legal_representative_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'application.vacant.headquarter.entity.person.first_last_name', 'key' => ':legal_representative_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'application.vacant.headquarter.entity.person.second_last_name', 'key' => ':legal_representative_second_last_name'],
                                        ['label' => 'Tipo documento', 'field' => 'application.vacant.headquarter.entity.person.document_type.settings.abreviature', 'key' => ':legal_representative_document_type'],
                                        ['label' => 'Documento', 'field' => 'application.vacant.headquarter.entity.person.document_number', 'key' => ':legal_representative_document_number'],
                                        ['label' => 'Firma', 'field' => 'legal_representative_signature.path', 'key' => ':legal_representative_signature', 'type' => 'file']
                                    ]
                                ],
                                [
                                    'title' => 'Sede',
                                    'path'  => 'application.vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'Departamento', 'field' => 'city.department.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad',  'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address'],
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Candidato',
                            'path'  => 'contract',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'person.first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'person.second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'person.first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'person.second_last_name', 'key' => ':person_second_last_name'],
                                        ['label' => 'Firma', 'field' => 'person_signature.path', 'key' => ':person_signature', 'type' => 'file']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Contrato',
                            'path'  => 'contract',
                            'groups' => [
                                [
                                    'title' => 'Información relevante',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Cargo', 'field' => 'position.name', 'key' => ':contract_position'],
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_contract_type'],
                                        ['label' => 'Periodo de pago', 'field' => 'payment_period.name', 'key' => ':contract_payment_period'],
                                        ['label' => 'Salario', 'field' => 'salary', 'key' => ':contract_salary'],
                                        ['label' => 'Auxilio de transporte', 'field' => 'transport_allowance', 'key' => ':contract_transport_allowance'],
                                        ['label' => 'Fecha inicio labores', 'field' => 'start_date', 'key' => ':contract_start_date'],
                                        ['label' => 'Fecha fin labores', 'field' => 'end_date', 'key' => ':contract_end_date']
                                    ]
                                ]
                            ]
                        ],
                    ]
                ]
            ],
            (object) [
                'name' => 'Acuerdo de Confidencialidad',
                'description' => 'Plantilla para protección de información sensible (NDA).',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Empleador',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Empresa',
                                    'path'  => 'vacant.headquarter.entity.enterprise',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field'  => 'name', 'key' => ':enterprise_name'],
                                        ['label' => 'Descripcion', 'field'  => 'description', 'key' => ':enterprise_description']
                                    ]
                                ],
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_entity_type'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Representante legal',
                                    'path'  => 'vacant.headquarter.entity.person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':legal_representative_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':legal_representative_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':legal_representative_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':legal_representative_second_last_name'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':legal_representative_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':legal_representative_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Sede',
                                    'path'  => 'vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'Departamento', 'field' => 'city.department.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad',  'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address'],
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Candidato',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                    ]
                ]
            ],
            (object) [
                'name' => 'Autorización de Tratamiento de Datos',
                'description' => 'Documento para consentir uso de datos personales conforme a la ley.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Empleador',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Empresa',
                                    'path'  => 'vacant.headquarter.entity.enterprise',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field'  => 'name', 'key' => ':enterprise_name'],
                                        ['label' => 'Descripcion', 'field'  => 'description', 'key' => ':enterprise_description']
                                    ]
                                ],
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_entity_type'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Representante legal',
                                    'path'  => 'vacant.headquarter.entity.person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':legal_representative_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':legal_representative_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':legal_representative_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':legal_representative_second_last_name'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':legal_representative_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':legal_representative_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Sede',
                                    'path'  => 'vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'Departamento', 'field' => 'city.department.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad',  'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address'],
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Candidato',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                    ]
                ]
            ],
            (object) ['name' => 'Otro Sí / Modificación', 'description' => 'Formato para adicionar, modificar o extender condiciones del acuerdo original.'],
            (object) ['name' => 'Certificación Laboral', 'description' => 'Formato para certificar vínculo, cargo y tiempo de servicio de una persona.'],
            (object) ['name' => 'Acta de Inicio', 'description' => 'Documento que da formal inicio a la ejecución de un contrato.'],
            (object) ['name' => 'Notificación de Finalización de Contrato', 'description' => 'Aviso formal de terminación de la relación contractual.'],
            (object) ['name' => 'Acta de Terminación', 'description' => 'Documento que deja constancia de la finalización y entrega de obligaciones.'],
            (object) ['name' => 'Diploma', 'description' => 'Documento oficial que acredita un título obtenido.'],
            (object) ['name' => 'Certificado Académico', 'description' => 'Constancia de aprobación de estudios o módulos.'],
            (object) ['name' => 'Certificado Laboral', 'description' => 'Documento formal que certifica vínculo laboral y condiciones.'],
            (object) ['name' => 'Hoja de Vida / Currículum Vitae', 'description' => 'Documento que resume trayectoria académica, laboral y habilidades del individuo.'],
            (object) ['name' => 'Foto Personal', 'description' => 'Fotografía tipo documento o imagen personal para trámites.'],
            (object) ['name' => 'Imagen', 'description' => 'Archivo visual.'],
            (object) ['name' => 'Video', 'description' => 'Archivo audiovisual.'],
            (object) ['name' => 'Audio', 'description' => 'Archivo de grabación sonora.'],
            (object) ['name' => 'Anexos', 'description' => 'Documentos anexos correspondientes a la persona, que incluyen antecedentes, certificados, afiliaciones y demás soportes requeridos para los procesos administrativos o de validación.'],
            (object) ['name' => 'Firma', 'description' => 'Documento digital que contiene la firma del representante o usuario autorizado.'],
            (object) ['name' => 'PDF Contractual', 'description' => 'PDF generado automáticamente a partir de una plantilla contractual del sistema.'],
            (object) ['name' => 'Cuenta de Cobro', 'description' => 'Documento presentado por un contratista o proveedor para solicitar el pago de servicios prestados, detallando valores, fechas y condiciones.'],
            (object) ['name' => 'Informe de Actividades Realizadas', 'description' => 'Documento que describe las tareas, entregables y avances ejecutados durante un periodo específico, generalmente usado como soporte para pagos o seguimiento contractual.']
        ];

        foreach ($subitems as $subitem) {
            $fileSubtype = new FileSubtype();
            $fileSubtype->name = $subitem->name;
            $fileSubtype->description = $subitem->description;
            $fileSubtype->settings = $subitem->settings ?? [];
            $fileSubtype->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Formulario';
        $item->description = 'Clasificación de los formularios disponibles.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Prueba Inicial',
                'description' => 'Formulario aplicado al inicio del proceso de selección o diagnóstico.',
                'settings' => (object) [
                    'validations' => [
                        'assignable_type' => 'in:' . implode(',', [Enterprise::class, Entity::class, Headquarter::class]),
                        'model_type' => 'in:' . implode(',', [Area::class, Position::class]),
                    ]
                ]
            ],
            (object) [
                'name' => 'Prueba Técnica',
                'description' => 'Formulario para evaluar conocimientos o habilidades específicas según el rol.',
                'settings' => (object) [
                    'validations' => [
                        'assignable_type' => 'in:' . implode(',', [Enterprise::class, Entity::class, Headquarter::class]),
                        'model_type' => 'in:' . implode(',', [Area::class, Position::class]),
                    ]
                ]
            ],
            (object) [
                'name' => 'Prueba Psicotécnica',
                'description' => 'Formulario para aplicar pruebas de carácter psicométrico o de perfil conductual.',
                'settings' => (object) [
                    'validations' => [
                        'assignable_type' => 'in:' . implode(',', [Enterprise::class, Entity::class, Headquarter::class]),
                        'model_type' => 'in:' . implode(',', [null, Area::class, Position::class]),
                    ]
                ]
            ],
            (object) [
                'name' => 'Encuesta',
                'description' => 'Formulario para recolectar opiniones, impresiones o retroalimentación.',
                'settings' => (object) [
                    'validations' => [
                        'assignable_type' => 'in:' . implode(',', [null, Enterprise::class, Entity::class, Headquarter::class]),
                        'model_type' => 'in:' . implode(',', [null, Area::class, Position::class]),
                    ]
                ]
            ],
            (object) [
                'name' => 'Información Complementaria',
                'description' => 'Formulario para recopilar datos adicionales requeridos según el proceso.',
                'settings' => (object) [
                    'validations' => [
                        'assignable_type' => 'in:' . implode(',', [null, Enterprise::class, Entity::class, Headquarter::class]),
                        'model_type' => 'in:' . implode(',', [null, Area::class, Position::class]),
                    ]
                ]
            ],
            (object) [
                'name' => 'Inducción',
                'description' => 'Tipo de formulario utilizado para registrar, gestionar y documentar el proceso de inducción de los colaboradores, incluyendo la información necesaria para su integración a la organización.',
                'settings' => (object) [
                    'validations' => [
                        'assignable_type' => 'in:' . implode(',', [null, Enterprise::class, Entity::class, Headquarter::class]),
                        'model_type' => 'in:' . implode(',', [null, Area::class, Position::class]),
                    ]
                ]
            ],
            (object) ['name' => 'Evaluación Video Entrevista', 'description' => 'Formulario para valorar la video entrevista.'],
            (object) [
                'name' => 'Calificación de la Empresa',
                'description' => 'Formulario para evaluar la percepción, satisfacción o reputación de la empresa.',
                'settings' => (object) [
                    'validations' => [
                        'assignable_type' => 'in:' . implode(',', [Entity::class]),
                        'model_type' => 'in:' . implode(',', [null]),
                    ]
                ]
            ]
        ];

        foreach ($subitems as $subitem) {
            $formType = new FormType();
            $formType->name = $subitem->name;
            $formType->description = $subitem->description;
            $formType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Beneficios';
        $item->description = 'Lista de beneficios disponibles.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $benefit = new Benefit();
            $benefit->name = $subitem->name;
            $benefit->description = $subitem->description;
            $benefit->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Niveles de Idioma';
        $item->description = 'Clasificación de los niveles de competencia en un idioma.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Principiante',
                'description' => 'Puede comprender y utilizar expresiones cotidianas de uso muy frecuente.',
                'settings' => (object) [
                    'level' => 'A1'
                ]
            ],
            (object) [
                'name' => 'Básico',
                'description' => 'Puede comprender frases y expresiones de uso frecuente relacionadas con áreas de experiencia que le son especialmente relevantes.',
                'settings' => (object) [
                    'level' => 'A2'
                ]
            ],
            (object) [
                'name' => 'Intermedio',
                'description' => 'Puede comprender los puntos principales de textos claros en lengua estándar.',
                'settings' => (object) [
                    'level' => 'B1'
                ]
            ],
            (object) [
                'name' => 'Intermedio Alto',
                'description' => 'Puede entender las ideas principales de textos complejos y puede interactuar con fluidez.',
                'settings' => (object) [
                    'level' => 'B2'
                ]
            ],
            (object) [
                'name' => 'Avanzado',
                'description' => 'Puede comprender una amplia variedad de textos largos y exigentes, y reconocer su significado implícito.',
                'settings' => (object) [
                    'level' => 'C1'
                ]
            ],
            (object) [
                'name' => 'Experto',
                'description' => 'Puede comprender con facilidad prácticamente todo lo que oye o lee.',
                'settings' => (object) [
                    'level' => 'C2'
                ]
            ],
            (object) [
                'name' => 'Nativo',
                'description' => 'Habla el idioma como lengua materna o con total dominio natural.',
                'settings' => (object) [
                    'level' => 'N'
                ]
            ]
        ];

        foreach ($subitems as $subitem) {
            $languageLevel = new LanguageLevel();
            $languageLevel->name = $subitem->name;
            $languageLevel->description = $subitem->description;
            $languageLevel->settings = $subitem->settings;
            $languageLevel->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Idiomas';
        $item->description = 'Lista de idiomas con sus respectivos códigos.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Afar', 'description' => 'aa'],
            (object) ['name' => 'Abjasio', 'description' => 'ab'],
            (object) ['name' => 'Avéstico', 'description' => 'ae'],
            (object) ['name' => 'Afrikáans', 'description' => 'af'],
            (object) ['name' => 'Akan', 'description' => 'ak'],
            (object) ['name' => 'Amárico', 'description' => 'am'],
            (object) ['name' => 'Aragonés', 'description' => 'an'],
            (object) ['name' => 'Árabe', 'description' => 'ar'],
            (object) ['name' => 'Asamés', 'description' => 'as'],
            (object) ['name' => 'Avar', 'description' => 'av'],
            (object) ['name' => 'Aimara', 'description' => 'ay'],
            (object) ['name' => 'Azerí', 'description' => 'az'],
            (object) ['name' => 'Baskir', 'description' => 'ba'],
            (object) ['name' => 'Bielorruso', 'description' => 'be'],
            (object) ['name' => 'Búlgaro', 'description' => 'bg'],
            (object) ['name' => 'Bihari', 'description' => 'bh'],
            (object) ['name' => 'Bislama', 'description' => 'bi'],
            (object) ['name' => 'Bambara', 'description' => 'bm'],
            (object) ['name' => 'Bengalí', 'description' => 'bn'],
            (object) ['name' => 'Tibetano', 'description' => 'bo'],
            (object) ['name' => 'Bretón', 'description' => 'br'],
            (object) ['name' => 'Bosnio', 'description' => 'bs'],
            (object) ['name' => 'Catalán', 'description' => 'ca'],
            (object) ['name' => 'Checheno', 'description' => 'ce'],
            (object) ['name' => 'Chamorro', 'description' => 'ch'],
            (object) ['name' => 'Corso', 'description' => 'co'],
            (object) ['name' => 'Cree', 'description' => 'cr'],
            (object) ['name' => 'Checo', 'description' => 'cs'],
            (object) ['name' => 'Eslavo eclesiástico', 'description' => 'cu'],
            (object) ['name' => 'Chuvasio', 'description' => 'cv'],
            (object) ['name' => 'Galés', 'description' => 'cy'],
            (object) ['name' => 'Danés', 'description' => 'da'],
            (object) ['name' => 'Alemán', 'description' => 'de'],
            (object) ['name' => 'Divehi', 'description' => 'dv'],
            (object) ['name' => 'Dzongkha', 'description' => 'dz'],
            (object) ['name' => 'Ewé', 'description' => 'ee'],
            (object) ['name' => 'Griego', 'description' => 'el'],
            (object) ['name' => 'Inglés', 'description' => 'en'],
            (object) ['name' => 'Esperanto', 'description' => 'eo'],
            (object) ['name' => 'Español', 'description' => 'es'],
            (object) ['name' => 'Estonio', 'description' => 'et'],
            (object) ['name' => 'Euskera', 'description' => 'eu'],
            (object) ['name' => 'Persa', 'description' => 'fa'],
            (object) ['name' => 'Fula', 'description' => 'ff'],
            (object) ['name' => 'Finés', 'description' => 'fi'],
            (object) ['name' => 'Fiyiano', 'description' => 'fj'],
            (object) ['name' => 'Feroés', 'description' => 'fo'],
            (object) ['name' => 'Francés', 'description' => 'fr'],
            (object) ['name' => 'Frisón occidental', 'description' => 'fy'],
            (object) ['name' => 'Irlandés', 'description' => 'ga'],
            (object) ['name' => 'Gaélico escocés', 'description' => 'gd'],
            (object) ['name' => 'Gallego', 'description' => 'gl'],
            (object) ['name' => 'Guaraní', 'description' => 'gn'],
            (object) ['name' => 'Gujarati', 'description' => 'gu'],
            (object) ['name' => 'Manés', 'description' => 'gv'],
            (object) ['name' => 'Hausa', 'description' => 'ha'],
            (object) ['name' => 'Hebreo', 'description' => 'he'],
            (object) ['name' => 'Hindi', 'description' => 'hi'],
            (object) ['name' => 'Hiri motu', 'description' => 'ho'],
            (object) ['name' => 'Croata', 'description' => 'hr'],
            (object) ['name' => 'Haitiano', 'description' => 'ht'],
            (object) ['name' => 'Húngaro', 'description' => 'hu'],
            (object) ['name' => 'Armenio', 'description' => 'hy'],
            (object) ['name' => 'Herero', 'description' => 'hz'],
            (object) ['name' => 'Interlingua', 'description' => 'ia'],
            (object) ['name' => 'Indonesio', 'description' => 'id'],
            (object) ['name' => 'Interlingue', 'description' => 'ie'],
            (object) ['name' => 'Igbo', 'description' => 'ig'],
            (object) ['name' => 'Yi de Sichuan', 'description' => 'ii'],
            (object) ['name' => 'Inupiaq', 'description' => 'ik'],
            (object) ['name' => 'Ido', 'description' => 'io'],
            (object) ['name' => 'Islandés', 'description' => 'is'],
            (object) ['name' => 'Italiano', 'description' => 'it'],
            (object) ['name' => 'Inuktitut', 'description' => 'iu'],
            (object) ['name' => 'Japonés', 'description' => 'ja'],
            (object) ['name' => 'Javanés', 'description' => 'jv'],
            (object) ['name' => 'Georgiano', 'description' => 'ka'],
            (object) ['name' => 'Kongo', 'description' => 'kg'],
            (object) ['name' => 'Kikuyu', 'description' => 'ki'],
            (object) ['name' => 'Kuanyama', 'description' => 'kj'],
            (object) ['name' => 'Kazajo', 'description' => 'kk'],
            (object) ['name' => 'Groenlandés', 'description' => 'kl'],
            (object) ['name' => 'Jemer', 'description' => 'km'],
            (object) ['name' => 'Canarés', 'description' => 'kn'],
            (object) ['name' => 'Coreano', 'description' => 'ko'],
            (object) ['name' => 'Kanuri', 'description' => 'kr'],
            (object) ['name' => 'Cachemiro', 'description' => 'ks'],
            (object) ['name' => 'Kurdo', 'description' => 'ku'],
            (object) ['name' => 'Komi', 'description' => 'kv'],
            (object) ['name' => 'Córnico', 'description' => 'kw'],
            (object) ['name' => 'Kirguís', 'description' => 'ky'],
            (object) ['name' => 'Latín', 'description' => 'la'],
            (object) ['name' => 'Luxemburgués', 'description' => 'lb'],
            (object) ['name' => 'Ganda', 'description' => 'lg'],
            (object) ['name' => 'Limburgués', 'description' => 'li'],
            (object) ['name' => 'Lingala', 'description' => 'ln'],
            (object) ['name' => 'Lao', 'description' => 'lo'],
            (object) ['name' => 'Lituano', 'description' => 'lt'],
            (object) ['name' => 'Luba-Katanga', 'description' => 'lu'],
            (object) ['name' => 'Letón', 'description' => 'lv'],
            (object) ['name' => 'Malgache', 'description' => 'mg'],
            (object) ['name' => 'Marshalés', 'description' => 'mh'],
            (object) ['name' => 'Maorí', 'description' => 'mi'],
            (object) ['name' => 'Macedonio', 'description' => 'mk'],
            (object) ['name' => 'Malayalam', 'description' => 'ml'],
            (object) ['name' => 'Mongol', 'description' => 'mn'],
            (object) ['name' => 'Maratí', 'description' => 'mr'],
            (object) ['name' => 'Malayo', 'description' => 'ms'],
            (object) ['name' => 'Maltés', 'description' => 'mt'],
            (object) ['name' => 'Birmano', 'description' => 'my'],
            (object) ['name' => 'Nauruano', 'description' => 'na'],
            (object) ['name' => 'Noruego bokmål', 'description' => 'nb'],
            (object) ['name' => 'Ndebele del norte', 'description' => 'nd'],
            (object) ['name' => 'Nepalí', 'description' => 'ne'],
            (object) ['name' => 'Ndonga', 'description' => 'ng'],
            (object) ['name' => 'Neerlandés', 'description' => 'nl'],
            (object) ['name' => 'Noruego nynorsk', 'description' => 'nn'],
            (object) ['name' => 'Noruego', 'description' => 'no'],
            (object) ['name' => 'Ndebele del sur', 'description' => 'nr'],
            (object) ['name' => 'Navajo', 'description' => 'nv'],
            (object) ['name' => 'Chichewa', 'description' => 'ny'],
            (object) ['name' => 'Occitano', 'description' => 'oc'],
            (object) ['name' => 'Ojibwa', 'description' => 'oj'],
            (object) ['name' => 'Oromo', 'description' => 'om'],
            (object) ['name' => 'Oriya', 'description' => 'or'],
            (object) ['name' => 'Osetio', 'description' => 'os'],
            (object) ['name' => 'Panyabí', 'description' => 'pa'],
            (object) ['name' => 'Pali', 'description' => 'pi'],
            (object) ['name' => 'Polaco', 'description' => 'pl'],
            (object) ['name' => 'Pastún', 'description' => 'ps'],
            (object) ['name' => 'Portugués', 'description' => 'pt'],
            (object) ['name' => 'Quechua', 'description' => 'qu'],
            (object) ['name' => 'Romanche', 'description' => 'rm'],
            (object) ['name' => 'Kirundi', 'description' => 'rn'],
            (object) ['name' => 'Rumano', 'description' => 'ro'],
            (object) ['name' => 'Ruso', 'description' => 'ru'],
            (object) ['name' => 'Kinyarwanda', 'description' => 'rw'],
            (object) ['name' => 'Sánscrito', 'description' => 'sa'],
            (object) ['name' => 'Sardo', 'description' => 'sc'],
            (object) ['name' => 'Sindhi', 'description' => 'sd'],
            (object) ['name' => 'Sami septentrional', 'description' => 'se'],
            (object) ['name' => 'Sango', 'description' => 'sg'],
            (object) ['name' => 'Cingalés', 'description' => 'si'],
            (object) ['name' => 'Eslovaco', 'description' => 'sk'],
            (object) ['name' => 'Esloveno', 'description' => 'sl'],
            (object) ['name' => 'Samoano', 'description' => 'sm'],
            (object) ['name' => 'Shona', 'description' => 'sn'],
            (object) ['name' => 'Somalí', 'description' => 'so'],
            (object) ['name' => 'Albanés', 'description' => 'sq'],
            (object) ['name' => 'Serbio', 'description' => 'sr'],
            (object) ['name' => 'Suazi', 'description' => 'ss'],
            (object) ['name' => 'Sesotho', 'description' => 'st'],
            (object) ['name' => 'Sundanés', 'description' => 'su'],
            (object) ['name' => 'Sueco', 'description' => 'sv'],
            (object) ['name' => 'Suajili', 'description' => 'sw'],
            (object) ['name' => 'Tamil', 'description' => 'ta'],
            (object) ['name' => 'Telugu', 'description' => 'te'],
            (object) ['name' => 'Tayiko', 'description' => 'tg'],
            (object) ['name' => 'Tailandés', 'description' => 'th'],
            (object) ['name' => 'Tigriña', 'description' => 'ti'],
            (object) ['name' => 'Turcomano', 'description' => 'tk'],
            (object) ['name' => 'Tagalo', 'description' => 'tl'],
            (object) ['name' => 'Tswana', 'description' => 'tn'],
            (object) ['name' => 'Tongano', 'description' => 'to'],
            (object) ['name' => 'Turco', 'description' => 'tr'],
            (object) ['name' => 'Tsonga', 'description' => 'ts'],
            (object) ['name' => 'Tártaro', 'description' => 'tt'],
            (object) ['name' => 'Twi', 'description' => 'tw'],
            (object) ['name' => 'Tahitiano', 'description' => 'ty'],
            (object) ['name' => 'Uigur', 'description' => 'ug'],
            (object) ['name' => 'Ucraniano', 'description' => 'uk'],
            (object) ['name' => 'Urdu', 'description' => 'ur'],
            (object) ['name' => 'Uzbeko', 'description' => 'uz'],
            (object) ['name' => 'Venda', 'description' => 've'],
            (object) ['name' => 'Vietnamita', 'description' => 'vi'],
            (object) ['name' => 'Volapük', 'description' => 'vo'],
            (object) ['name' => 'Valón', 'description' => 'wa'],
            (object) ['name' => 'Wolof', 'description' => 'wo'],
            (object) ['name' => 'Xhosa', 'description' => 'xh'],
            (object) ['name' => 'Yidis', 'description' => 'yi'],
            (object) ['name' => 'Yoruba', 'description' => 'yo'],
            (object) ['name' => 'Zhuang', 'description' => 'za'],
            (object) ['name' => 'Chino', 'description' => 'zh'],
            (object) ['name' => 'Zulú', 'description' => 'zu',]
        ];

        foreach ($subitems as $subitem) {
            $language = new Language();
            $language->name = $subitem->name;
            $language->description = $subitem->description;
            $language->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Plataformas de Notificación';
        $item->description = 'Plataformas o proveedores utilizados para el envío de notificaciones.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Hablame',
                'description' => 'Proveedor colombiano para el envío de mensajes SMS, WhatsApp y voz.'
            ],
            (object) [
                'name' => 'Masiv',
                'description' => 'Plataforma de comunicación omnicanal especializada en campañas masivas SMS y WhatsApp.'
            ]
        ];

        foreach ($subitems as $subitem) {
            $notificationPlatform = new NotificationPlatform();
            $notificationPlatform->name = $subitem->name;
            $notificationPlatform->description = $subitem->description;
            $notificationPlatform->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Canales de Notificación';
        $item->description = 'Canales disponibles para enviar notificaciones a los usuarios.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'SMS',
                'description' => 'Notificación enviada como mensaje de texto a un número de teléfono móvil.',
                'settings' => [
                    'platforms' => [
                        (object) [
                            'notification_platform_id' => 333,
                            'active' => false,
                            'variables' => [
                                'url' => [
                                    'label' => 'URL',
                                    'value' => 'https://api103.hablame.co/api/sms/v3/send/priority'
                                ],
                                'account' => [
                                    'label' => 'Cuenta',
                                    'value' => 'CAMBIAR_CUENTA'
                                ],
                                'api_key' => [
                                    'label' => 'Llave API',
                                    'value' => 'CAMBIAR_API_KEY'
                                ],
                                'token' => [
                                    'label' => 'Token',
                                    'value' => 'CAMBIAR_TOKEN'
                                ]
                            ]
                        ],
                        (object) [
                            'notification_platform_id' => 334,
                            'active' => true,
                            'variables' => [
                                'url' => [
                                    'label' => 'URL',
                                    'value' => 'https://api-sms.masivapp.com/send-message'
                                ],
                                'user' => [
                                    'label' => 'Usuario',
                                    'value' => 'CAMBIAR_USUARIO'
                                ],
                                'password' => [
                                    'label' => 'Contraseña',
                                    'value' => 'CAMBIAR_CLAVE'
                                ],
                            ]
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Correo Electrónico',
                'description' => 'Notificación enviada al correo electrónico del usuario.',
                'settings' => [
                    'platforms' => [
                        (object) [
                            'notification_platform_id' => 333,
                            'active' => false,
                            'variables' => [
                                'url' => [
                                    'label' => 'URL',
                                    'value' => 'https://www.hablame.co/api/email/v5/send'
                                ],
                                'email' => [
                                    'label' => 'Correo',
                                    'value' => 'correo@example.com'
                                ],
                                'token' => [
                                    'label' => 'Token',
                                    'value' => 'CAMBIAR_TOKEN'
                                ]
                            ]
                        ],
                        (object) [
                            'notification_platform_id' => 334,
                            'active' => true,
                            'variables' => [
                                'url' => [
                                    'label' => 'URL',
                                    'value' => 'https://api.masiv.masivian.com/email/v1/delivery'
                                ],
                                'email' => [
                                    'label' => 'Correo',
                                    'value' => 'correo@example.com'
                                ],
                                'user' => [
                                    'label' => 'Usuario',
                                    'value' => 'CAMBIAR_USUARIO'
                                ],
                                'password' => [
                                    'label' => 'Contraseña',
                                    'value' => 'CAMBIAR_CLAVE'
                                ],
                            ]
                        ]
                    ]
                ]
            ],
            (object) ['name' => 'WhatsApp', 'description' => 'Notificación enviada a través de la plataforma de mensajería WhatsApp.'],
            (object) ['name' => 'Llamada Telefónica', 'description' => 'Notificación mediante una llamada de voz al número registrado.'],
            (object) ['name' => 'Mensaje Interno', 'description' => 'Notificación mostrada dentro del sistema o plataforma.']
        ];

        foreach ($subitems as $subitem) {
            $notificationChannel = new NotificationChannel();
            $notificationChannel->name = $subitem->name;
            $notificationChannel->description = $subitem->description;
            $notificationChannel->settings = $subitem->settings ?? [];
            $notificationChannel->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'ARL';
        $item->description = 'Aseguradoras de Riesgos Laborales en Colombia.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $riskManager = new RiskManager();
            $riskManager->name = $subitem->name;
            $riskManager->description = $subitem->description;
            $riskManager->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipo de entidad de salud';
        $item->description = 'Tipos de entidades Prestadoras de Salud.';
        $item->save();

        $subitems = [
            (object) ['name' => 'EPS', 'description' => 'Entidad Promotora de Salud responsable de la afiliación y el acceso a los servicios de salud.'],
            (object) ['name' => 'IPS', 'description' => 'Institución Prestadora de Servicios de Salud encargada de la atención médica directa al paciente.']
        ];

        foreach ($subitems as $subitem) {
            $healthEntityType = new HealthEntityType();
            $healthEntityType->name = $subitem->name;
            $healthEntityType->description = $subitem->description;
            $healthEntityType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Entidades de salud';
        $item->description = 'Entidades Promotoras de Salud en Colombia.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $healthEntity = new HealthEntity();
            $healthEntity->name = $subitem->name;
            $healthEntity->description = $subitem->description;
            $healthEntity->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Caja de Compensación';
        $item->description = 'Cajas de Compensación Familiar en Colombia.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $compensationFund = new CompensationFund();
            $compensationFund->name = $subitem->name;
            $compensationFund->description = $subitem->description;
            $compensationFund->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Fondo de Pensiones';
        $item->description = 'Fondos de pensiones en Colombia.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $pensionFund = new PensionFund();
            $pensionFund->name = $subitem->name;
            $pensionFund->description = $subitem->description;
            $pensionFund->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Habilidades';
        $item->description = 'Conjunto de competencias técnicas y blandas orientadas a manejar herramientas y procesos, comunicarse y colaborar eficazmente, planificar y liderar proyectos, e innovar para resolver problemas.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $skill = new Skill();
            $skill->name = $subitem->name;
            $skill->description = $subitem->description;
            $skill->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipo de empresa';
        $item->description = 'Divisiones o departamentos dentro de una organización, cada uno especializado en funciones y responsabilidades específicas que contribuyen al cumplimiento de los objetivos de la empresa.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Sociedad por Acciones Simplificada',
                'description' => 'Estructura flexible, responsabilidad limitada al aporte de capital y facilidad de constitución.',
                'settings' => (object) [
                    'abreviature' => 'S.A.S.'
                ]
            ],
            (object) [
                'name' => 'Sociedad de Responsabilidad Limitada',
                'description' => 'Responsabilidad limitada de los socios y participación no representada por acciones.',
                'settings' => (object) [
                    'abreviature' => 'Ltda.'
                ]
            ],
            (object) [
                'name' => 'Sociedad Anónima',
                'description' => 'Capital dividido en acciones libremente negociables, ideal para grandes inversiones.',
                'settings' => (object) [
                    'abreviature' => 'S.A.'
                ]
            ],
            (object) [
                'name' => 'Sociedad en Comandita por Acciones',
                'description' => 'Combina socios gestores con responsabilidad ilimitada y socios comanditarios con responsabilidad limitada.',
                'settings' => (object) [
                    'abreviature' => 'S.C.A.'
                ]
            ],
            (object) [
                'name' => 'Sociedad Colectiva',
                'description' => 'Todos los socios responden de forma solidaria e ilimitada por las obligaciones sociales.',
                'settings' => (object) [
                    'abreviature' => 'S.C.'
                ]
            ],
            (object) [
                'name' => 'Empresa Unipersonal',
                'description' => 'Constituida por una sola persona con patrimonio independiente del personal.',
                'settings' => (object) [
                    'abreviature' => 'E.U.'
                ]
            ],
            (object) [
                'name' => 'Cooperativa',
                'description' => 'Organización de economía solidaria, propiedad de sus asociados, para el beneficio común.',
                'settings' => (object) [
                    'abreviature' => 'Coop.'
                ]
            ],
            (object) [
                'name' => 'Fundación',
                'description' => 'Entidad sin ánimo de lucro creada para fines sociales, culturales o comunitarios.',
                'settings' => (object) [
                    'abreviature' => 'Fund.'
                ]
            ]
        ];

        foreach ($subitems as $subitem) {
            $entityType = new EntityType();
            $entityType->name = $subitem->name;
            $entityType->description = $subitem->description;
            $entityType->settings = $subitem->settings;
            $entityType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Documentos fiscales';
        $item->description = 'Son aquellos registros oficiales que acreditan y respaldan las obligaciones tributarias de una persona natural o jurídica frente a la administración tributaria.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $fiscalDocument = new FiscalDocument();
            $fiscalDocument->name = $subitem->name;
            $fiscalDocument->description = $subitem->description;
            $fiscalDocument->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Programas de vigilancia epidemiológica';
        $item->description = 'Es un conjunto de actividades de prevención, control y seguimiento orientadas a proteger la salud de los trabajadores, identificando y evaluando los riesgos ocupacionales a los que están expuestos.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $epidemiologicalProgram = new EpidemiologicalProgram();
            $epidemiologicalProgram->name = $subitem->name;
            $epidemiologicalProgram->description = $subitem->description;
            $epidemiologicalProgram->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Motivos solicitud de personal';
        $item->description = 'Son las razones o circunstancias que originan la necesidad de vincular nuevo talento a la organización, como renuncias, terminación de contrato, creación de nuevos cargos o incremento de la demanda de trabajo.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Renuncia', 'description' => 'Se presenta cuando un trabajador decide voluntariamente finalizar su vínculo laboral con la organización, generando la necesidad de cubrir su cargo.'],
            (object) ['name' => 'Terminación de contrato', 'description' => 'Ocurre cuando la relación laboral finaliza por cumplimiento de término, decisión de la empresa o causas legales, requiriendo reemplazar al colaborador saliente.'],
            (object) ['name' => 'Personal nuevo', 'description' => 'Se da cuando la organización necesita crear o ampliar cargos para responder a nuevas demandas, proyectos o crecimiento institucional.'],
        ];

        foreach ($subitems as $subitem) {
            $hiringReason = new HiringReason();
            $hiringReason->name = $subitem->name;
            $hiringReason->description = $subitem->description;
            $hiringReason->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de servicios';
        $item->description = 'Son las razones, circunstancias o apoyos externos que originan la necesidad de vincular o evaluar talento en la organización. Además de situaciones internas como renuncias, terminación de contrato o creación de nuevos cargos, también pueden incluir servicios especializados que prestan entidades externas para procesos de selección, validación o seguridad laboral.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Polígrafo', 'description' => 'Incluye pruebas y valoraciones realizadas por entidades especializadas externas, como el examen de polígrafo u otros servicios de validación, que apoyan los procesos de selección y garantizan la confiabilidad de la vinculación del talento.'],
        ];

        foreach ($subitems as $subitem) {
            $serviceType = new ServiceType();
            $serviceType->name = $subitem->name;
            $serviceType->description = $subitem->description;
            $serviceType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Proveedores de servicios';
        $item->description = 'Corresponden a entidades externas que brindan apoyo especializado en los procesos de gestión del talento humano. Estos servicios complementan la labor interna de la organización mediante evaluaciones, validaciones o pruebas que fortalecen la confiabilidad, la seguridad y la idoneidad en la vinculación de nuevos colaboradores.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $serviceProvider = new ServiceProvider();
            $serviceProvider->name = $subitem->name;
            $serviceProvider->description = $subitem->description;
            $serviceProvider->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Periodos de pago';
        $item->description = 'Los periodos de pago definen la frecuencia o condición bajo la cual se liquida y entrega la remuneración al colaborador. Estos ciclos permiten organizar la gestión de nómina, asegurar el cumplimiento normativo y adaptarse a modalidades contractuales o acuerdos específicos según la naturaleza del trabajo.';
        $item->save();

        $subitems = [
            (object) ['name' => 'Mensual', 'description' => 'Pago que se realiza una vez al mes, generalmente al cierre del periodo mensual o en una fecha establecida por la organización.'],
            (object) ['name' => 'Semanal', 'description' => 'Pago efectuado cada semana, aplicable a modalidades operativas o contratos por jornal donde los ciclos laborales son cortos.'],
            (object) ['name' => 'Quincenal', 'description' => 'Pago dividido en dos cortes dentro del mes, típicamente en los días 15 y 30, o según el calendario interno de la empresa.'],
            (object) ['name' => 'Por proyecto', 'description' => 'Pago que se liquida al finalizar un proyecto, hito o entregable específico, según los términos establecidos en el contrato.'],
            (object) ['name' => 'Cumplimiento de actividades', 'description' => 'Pago condicionado a la ejecución o finalización de tareas, actividades o metas definidas dentro del contrato o plan de trabajo.'],
            (object) ['name' => 'Al finalizar contrato', 'description' => 'Pago que se realiza al término de la relación laboral o contractual, liquidando conceptos pendientes según lo estipulado legalmente.'],
        ];

        foreach ($subitems as $subitem) {
            $paymentPeriod = new PaymentPeriod();
            $paymentPeriod->name = $subitem->name;
            $paymentPeriod->description = $subitem->description;
            $paymentPeriod->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de pólizas';
        $item->description = 'Clasificación de las pólizas emitidas por la compañía, agrupadas según su naturaleza, cobertura y características contractuales. Estos tipos permiten estandarizar la gestión, facilitar la administración de riesgos y garantizar que cada producto se ajuste a las necesidades específicas del asegurado.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $policyType = new PolicyType();
            $policyType->name = $subitem->name;
            $policyType->description = $subitem->description;
            $policyType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de otrosí';
        $item->description = 'Modificaciones, aclaraciones o adiciones realizadas a una póliza vigente. Estos otrosí permiten ajustar condiciones, actualizar información o complementar coberturas sin necesidad de emitir una nueva póliza, asegurando la continuidad contractual y la trazabilidad del cambio.';
        $item->settings = (object) [
            'fields' => [
                'headquarter_id' => [
                    'label' => 'Sede',
                    'rules' => ['required', 'nullable']
                ],
                'contract_type_id' => [
                    'label' => 'Tipo de contrato',
                    'rules' => ['required', 'nullable']
                ],
                'position_id' => [
                    'label' => 'Cargo',
                    'rules' => ['required', 'nullable']
                ],
                'salary' => [
                    'label' => 'Salario',
                    'rules' => ['required', 'nullable']
                ],
                'payment_period_id' => [
                    'label' => 'Periodo de pago',
                    'rules' => ['required', 'nullable']
                ],
                'start_date' => [
                    'label' => 'Fecha inicio de labores',
                    'rules' => ['required', 'nullable']
                ],
                'end_date' => [
                    'label' => 'Fecha fin de labores',
                    'rules' => ['required', 'nullable']
                ],
            ]
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $addendumType = new AddendumType();
            $addendumType->name = $subitem->name;
            $addendumType->description = $subitem->description;
            $addendumType->settings = $subitem->settings;
            $addendumType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Notificación';
        $item->description = 'Tipos de notificaciones para enviar a los usuarios.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Bienvenida como nuevooo usuario',
                'description' => 'Notificación enviada al usuario al completar exitosamente su registro en el sistema, con información de bienvenida y confirmación de la creación de su cuenta.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Usuario',
                            'path'  => 'user',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                    ],
                    'channels' => [
                        (object) [
                            "notification_channel_id" => 336,
                            "title" => "¡Bienvenido(a) a T-HÜMAN! 🚀 Tu cuenta ya está lista",
                            "message" => <<<HTML
                                <p class="isSelectedEnd">Hola <span class="var-chip" data-key=":person_first_name">[Primer nombre]</span>&nbsp;<span class="var-chip" data-key=":person_first_last_name">[Primer apellido]</span>&nbsp;</p>
                                <p class="isSelectedEnd">¡Nos alegra mucho darte la bienvenida a <strong>T-HÜMAN</strong>! 🎉</p>
                                <p class="isSelectedEnd">Tu <strong>registro se ha realizado con éxito</strong> y tu cuenta <strong>ya casi está lista</strong>. Solo falta un pequeño paso: ingresar a la plataforma y <strong>verificar que tus datos estén correctos</strong> para que puedas aprovechar al máximo todas las funcionalidades.</p>
                                <p class="isSelectedEnd"><br></p>
                                <p class="isSelectedEnd"><strong>Te recomendamos:</strong></p>
                                <ul>
                                    <li>Revisar y completar tu información personal.</li>
                                    <li>Verificar que tus datos de contacto estén correctos.</li>
                                    <li>Mantener tu perfil actualizado para mejorar tus oportunidades.</li>
                                </ul>
                                <p class="isSelectedEnd"><br></p>
                                <p class="isSelectedEnd">Si tienes alguna pregunta o necesitas ayuda, nuestro equipo estará encantado de apoyarte.</p>
                                <p class="isSelectedEnd">Gracias por confiar en <strong>T-HÜMAN</strong> para impulsar tu crecimiento profesional.</p>
                                <p class="isSelectedEnd">¡Muchos éxitos en esta nueva etapa! 🌟</p>
                                <p>Saludos cordiales,<br><strong>Equipo T-HÜMAN</strong></p>
                                HTML
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Solicitud de restablecimiento de contraseña',
                'description' => 'Notificación enviada al usuario para informarle que ha solicitado restablecer su contraseña y proporcionarle las instrucciones necesarias para completar el proceso.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Usuario',
                            'path'  => 'user',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ],
                        ],
                        [
                            'label' => 'Contraseña',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Autorización',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Contraseña', 'field' => 'password', 'key' => ':password']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => [
                        (object) [
                            "notification_channel_id" => 335,
                            "title" => "Restablecimiento de contraseña",
                            "message" => <<<HTML
                                <p>Hemos restablecido su contraseña de acceso a&nbsp;
                                    <span style="font-family: Arial, Helvetica, sans-serif; text-align: center; color: rgb(34, 34, 34); font-size: 16px;">
                                        <span style="font-size: 14px;">
                                            <b>T-HÜMAN. </b>Su nueva contraseña es:&nbsp;
                                        </span>
                                    </span>
                                    <span class="var-chip" data-key=":password">[Contraseña]</span>&nbsp;.
                                        Le recomendamos cambiarla inmediatamente después de iniciar sesión para mantener la seguridad de su cuenta.
                                        Al usarla, usted acepta nuestras&nbsp;
                                    <u style="color: rgb(91, 76, 208); font-size: 12px; text-align: center;">
                                        <span style="font-size: 14px;">políticas de privacidad</span>
                                    </u>&nbsp;y tratamiento de datos personales.
                                    <font color="#000000">
                                        <span style="font-size: 14px;">"</span>
                                        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; text-align: center;">
                                            Gracias por confiar en T-HÜMAN.&nbsp;
                                        </span>
                                        <em style="font-family: Arial, Helvetica, sans-serif; text-align: center; font-size: 12px;">
                                            <span style="font-size: 14px;">Conectando talentos, transformando futuro."</span>
                                        </em>
                                    </font>
                                </p>
                                HTML
                        ],
                        (object) [
                            "notification_channel_id" => 336,
                            "title" => "Restablecimiento de contraseña",
                            "message" => <<<HTML
                                <h2 style="color: rgb(91, 76, 208); font-size: 20px; font-family: Arial, Helvetica, sans-serif; text-align: center;">
                                    Recuperación de contraseña
                                </h2>

                                <p style="color: rgb(91, 76, 208); font-size: 20px; font-family: Arial, Helvetica, sans-serif; text-align: center; line-height: 1;">
                                    <span style="color: rgb(34, 34, 34); font-size: 16px;">
                                        Ha solicitado recuperar su contraseña de acceso a&nbsp;
                                    </span>
                                    <strong style="color: rgb(34, 34, 34); font-size: 16px;">T-HÜMAN.</strong>
                                    <span style="color: rgb(34, 34, 34); font-size: 16px;">
                                        A continuación encontrará su nueva contraseña temporal. Le recomendamos cambiarla inmediatamente después de iniciar sesión para mantener su cuenta segura.
                                    </span>
                                </p>
                                <p style="text-align: center;">
                                    <span class="var-chip" data-key=":password" style="font-size: 24px;">[Contraseña]</span>
                                </p>
                                <p style="text-align: center;">
                                    <span style="color: rgb(85, 85, 85); font-size: 14px;">
                                        Esta contraseña es confidencial y de uso único. No la comparta con nadie.
                                    </span><br>
                                    <span style="color: rgb(85, 85, 85); font-size: 14px;">
                                        Si usted no solicitó este cambio, por favor contacte a nuestro equipo de soporte de inmediato.
                                    </span>
                                </p>
                                <p style="text-align: center;">
                                    <span style="color: rgb(85, 85, 85); font-size: 14px;">
                                        ----------------------------------------------------------------------------
                                    </span>
                                </p>
                                <p style="text-align: center;">
                                    <span style="color: rgb(102, 102, 102); font-size: 12px;">
                                        Si no solicitó este cambio, ignore este mensaje o comuníquese con nosotros.
                                    </span><br><br>
                                    <span style="color: rgb(102, 102, 102); font-size: 12px;">
                                        Para más información sobre cómo protegemos sus datos, consulte nuestras
                                    </span>
                                    <u style="font-size: 12px;">políticas de privacidad</u>
                                    <span style="color: rgb(102, 102, 102); font-size: 12px;">
                                        o contacte a nuestro equipo:
                                    </span>
                                    <a href="mailto:soporte@ejemplo.com" style="color: rgb(91, 76, 208);">
                                        soporte@ejemplo.com
                                    </a>.
                                    <br><br>
                                    <span style="color: rgb(102, 102, 102); font-size: 12px;">
                                        Gracias por confiar en T-HÜMAN.
                                    </span><br>
                                    <em style="color: rgb(102, 102, 102); font-size: 12px;">
                                        Conectando talentos, transformando futuro.
                                    </em>
                                </p>
                                HTML
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Verificación de datos de usuario',
                'description' => 'Notificación enviada al usuario para informarle que su registro en la plataforma se realizó correctamente y recordarle que debe ingresar para revisar y verificar que su información personal esté completa y actualizada.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'person',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Codigo de verificación',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Autorización',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Codigo de verificación', 'field' => 'code', 'key' => ':code']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Notificación de restablecimiento de contraseña',
                'description' => 'Notificación enviada al usuario para informarle que su contraseña de acceso ha sido restablecida correctamente y que ya puede iniciar sesión con su nueva contraseña.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Usuario',
                            'path'  => 'user',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ],
                        ],
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Nuevas vacantes disponibles',
                'description' => 'Notificación enviada a las personas que siguen la empresa para informarles que se han publicado nuevas vacantes y motivarlas a ingresar a la plataforma para conocer los detalles y postularse.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'person',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'entity',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ],
                            ]
                        ],
                        [
                            'label' => 'Vacante',
                            'path'  => 'vacant',
                            'groups' => [
                                [
                                    'title' => 'Identificación',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Codigo', 'field' => 'uuid', 'key' => ':vacant_uuid']
                                    ]
                                ],
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Confirmación de aplicación a vacante del candidato',
                'description' => 'Notificación enviada al usuario para confirmarle que su aplicación a una vacante fue recibida correctamente y que su proceso de selección ha comenzado.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Actualización de estado de la aplicación',
                'description' => 'Notificación enviada al usuario para informarle que el estado de su aplicación a la vacante ha sido actualizado dentro del proceso de selección.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Información de la aplicación',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Estado', 'field' => 'status', 'key' => ':application_status'],
                                    ]
                                ],
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Agendamiento de entrevista',
                'description' => 'Notificación enviada al usuario para informarle que se ha programado una entrevista dentro del proceso de selección y proporcionarle los detalles necesarios para asistir.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'interview',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'interview',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'application.vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'interview',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'application.vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'application.vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Entrevista',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información de la entrevista',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de entrevista', 'field' => 'type', 'key' => ':interview_type'],
                                        ['label' => 'Fecha y hora', 'field' => 'interview.date_time', 'key' => ':interview_date_time'],
                                        ['label' => 'Indicaciones', 'field' => 'interview.indication', 'key' => ':interview_indication'],
                                        ['label' => 'Estado', 'field' => 'interview.status', 'key' => ':interview_status']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Agendamiento de pruebas',
                'description' => 'Notificación enviada al usuario para informarle que se ha programado una prueba dentro del proceso de selección y proporcionarle los detalles necesarios para realizarla.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'test',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'test',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'application.vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'test',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'application.vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'application.vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Pruebas',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información de la prueba',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de prueba', 'field' => 'type', 'key' => ':test_type'],
                                        ['label' => 'Fecha inicio', 'field' => 'test.start_date', 'key' => ':test_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'test.end_date', 'key' => ':test_end_date'],
                                        ['label' => 'Indicaciones', 'field' => 'test.indication', 'key' => ':test_indication'],
                                        ['label' => 'Estado', 'field' => 'test.status', 'key' => ':test_status']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Envío de token para realización de prueba',
                'description' => 'Notificación enviada al usuario para informarle que se ha generado un token de autorización que le permitirá acceder y realizar la prueba dentro del proceso de selección.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Pruebas',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información de la prueba',
                                    'path'  => 'test',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':test_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':test_end_date'],
                                        ['label' => 'Indicaciones', 'field' => 'indication', 'key' => ':test_indication'],
                                        ['label' => 'Estado', 'field' => 'status', 'key' => ':test_status']
                                    ]
                                ],
                                [
                                    'title' => 'Autorización',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Token', 'field' => 'token', 'key' => ':token']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Agendamiento de exámenes',
                'description' => 'Notificación enviada al usuario para informarle que se ha programado un examen dentro del proceso de selección y proporcionarle los detalles necesarios para realizarlo.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'exam',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'exam',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'application.vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'exam',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'application.vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'headquarter.name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'headquarter.city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'headquarter.city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'headquarter.city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'headquarter.address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Exámenes',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información del examen',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de examen', 'field' => 'type', 'key' => ':exam_type'],
                                        ['label' => 'Fecha inicio', 'field' => 'exam.start_date', 'key' => ':exam_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'exam.end_date', 'key' => ':exam_end_date'],
                                        ['label' => 'Indicaciones', 'field' => 'exam.indication', 'key' => ':exam_indication'],
                                        ['label' => 'Estado', 'field' => 'exam.status', 'key' => ':exam_status']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Solicitud de carga de documentación',
                'description' => 'Notificación enviada al usuario para informarle que debe cargar la documentación requerida como parte del proceso de selección.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Actualización de estado de documento',
                'description' => 'Notificación enviada al usuario para informarle que el estado del documento cargado ha sido actualizado dentro del proceso de selección.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'document',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Documentación',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información del documento',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de documento', 'field' => 'type', 'key' => ':document_type'],
                                        ['label' => 'Observación', 'field' => 'document.observation', 'key' => ':document_observation'],
                                        ['label' => 'Estado', 'field' => 'document.status', 'key' => ':document_status']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Envío de token para firma de contrato',
                'description' => 'Notificación enviada al usuario para informarle que se ha generado un token de autorización que le permitirá acceder al proceso de firma de contrato dentro de la plataforma.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'test',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'application.vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'test',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'application.vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'application.vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'application.vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Contrato',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Autorización',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Token', 'field' => 'token', 'key' => ':token']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Desistimiento del proceso de selección',
                'description' => 'Notificación enviada al usuario para informarle que su participación en el proceso de selección ha finalizado debido a no presentación, no realización o desistimiento voluntario durante alguna de las etapas del proceso.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'process',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'application.person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'process',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'application.vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información de la aplicación',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de proceso', 'field' => 'type', 'key' => ':process_type'],
                                        ['label' => 'Estado', 'field' => 'process.application.status', 'key' => ':application_status']
                                    ]
                                ],
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'process.application.vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'process.application.vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'process.application.vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'process.application.vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Finalización del proceso de selección',
                'description' => 'Notificación enviada al usuario para informarle que el proceso de selección ha finalizado y comunicarle el resultado final del proceso.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empresa',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'vacant.headquarter.entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_name'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Aplicación',
                            'path'  => 'application',
                            'groups' => [
                                [
                                    'title' => 'Información de la aplicación',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Estado', 'field' => 'status', 'key' => ':application_status']
                                    ]
                                ],
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'vacant.position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'vacant.headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ],
                                [
                                    'title' => 'Condiciones laborales',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_type_name'],
                                        ['label' => 'Modalidad de trabajo', 'field' => 'work_arrangement.name', 'key' => ':work_arrangement_name'],
                                        ['label' => 'Salario minimo', 'field' => 'salary_min', 'key' => ':vacant_salary_min'],
                                        ['label' => 'Salario maximo', 'field' => 'salary_max', 'key' => ':vacant_salary_max']
                                    ]
                                ],
                                [
                                    'title' => 'Fechas',
                                    'path'  => 'vacant',
                                    'fields' => [
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':vacant_start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':vacant_end_date']
                                    ]
                                ]
                            ]
                        ],
                    ],
                    'channels' => []
                ]
            ],
            (object) [
                'name' => 'Bienvenida como nuevo empleado',
                'description' => 'Notificación enviada al usuario para darle la bienvenida como nuevo empleado de la organización y brindarle información inicial para comenzar su experiencia dentro de la empresa.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => 'employee',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empleado',
                            'path'  => 'employee',
                            'groups' => [
                                [
                                    'title' => 'Información del cargo',
                                    'path'  => 'position',
                                    'fields' => [
                                        ['label' => 'Area', 'field' => 'area.name', 'key' => ':area_name'],
                                        ['label' => 'Cargo', 'field' => 'name', 'key' => ':position_name']
                                    ]
                                ],
                                [
                                    'title' => 'Ubicación',
                                    'path'  => 'headquarter',
                                    'fields' => [
                                        ['label' => 'Sede', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':headquarter_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Empleador',
                            'path'  => 'employee.headquarter',
                            'groups' => [
                                [
                                    'title' => 'Empresa',
                                    'path'  => 'entity.enterprise',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field'  => 'name', 'key' => ':enterprise_name'],
                                        ['label' => 'Descripcion', 'field'  => 'description', 'key' => ':enterprise_description']
                                    ]
                                ],
                                [
                                    'title' => 'Entidad',
                                    'path'  => 'entity',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_entity_type'],
                                        ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                        ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Representante legal',
                                    'path'  => 'entity.person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':legal_representative_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':legal_representative_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':legal_representative_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':legal_representative_second_last_name'],
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':legal_representative_document_type'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':legal_representative_document_number']
                                    ]
                                ],
                                [
                                    'title' => 'Sede',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Nombre', 'field' => 'name', 'key' => ':headquarter_name'],
                                        ['label' => 'Departamento', 'field' => 'city.department.name', 'key' => ':headquarter_departament'],
                                        ['label' => 'Ciudad',  'field' => 'city.name', 'key' => ':headquarter_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address'],
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'channels' => []
                ]
            ],
        ];

        foreach ($subitems as $subitem) {
            $notificationType = new NotificationType();
            $notificationType->name = $subitem->name;
            $notificationType->description = $subitem->description;
            $notificationType->settings = $subitem->settings;
            $notificationType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Permisos';
        $item->description = 'Clasificación de los permisos laborales otorgados a los trabajadores para ausentarse de sus funciones durante un período determinado, ya sea por motivos personales, familiares, médicos o legales. Estos permisos permiten gestionar de forma controlada y trazable las ausencias, garantizando el cumplimiento de la normativa laboral y las políticas internas de la organización.';
        $item->settings = (object) [
            'variables' => [
                [
                    'label' => 'Empleado',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Datos personales',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                            ]
                        ],
                        [
                            'title' => 'Datos de identificación',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                            ]
                        ],
                        [
                            'title' => 'Lugar y fecha de nacimiento',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                            ]
                        ],
                        [
                            'title' => 'Datos de contacto',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                            ]
                        ],
                        [
                            'title' => 'Residencia actual',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                            ]
                        ]
                    ]
                ],
                [
                    'label' => 'Permiso',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Información',
                            'path'  => '',
                            'fields' => [
                                ['label' => 'Tipo de permiso', 'field' => 'absence_type.name', 'key' => ':absence_type_name'],
                                ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                                ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                                ['label' => 'Compensación', 'field' => 'compensation', 'key' => ':compensation']
                            ]
                        ]
                    ]
                ],
            ],
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $absenceType = new AbsenceType();
            $absenceType->name = $subitem->name;
            $absenceType->description = $subitem->description;
            $absenceType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Vacaciones';
        $item->description = 'Clasificación de las modalidades de vacaciones otorgadas a los trabajadores, que definen las condiciones, duración y forma de disfrute del descanso remunerado conforme a la normativa laboral vigente y las políticas internas de la organización. Estas categorías permiten una gestión clara, controlada y trazable de los períodos de descanso, garantizando el cumplimiento legal y la correcta planificación del talento humano.';
        $item->settings = (object) [
            'variables' => [
                [
                    'label' => 'Empleado',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Datos personales',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                            ]
                        ],
                        [
                            'title' => 'Datos de identificación',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                            ]
                        ],
                        [
                            'title' => 'Lugar y fecha de nacimiento',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                            ]
                        ],
                        [
                            'title' => 'Datos de contacto',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                            ]
                        ],
                        [
                            'title' => 'Residencia actual',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                            ]
                        ]
                    ]
                ],
                [
                    'label' => 'Vacaciones',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Información',
                            'path'  => '',
                            'fields' => [
                                ['label' => 'Tipo de vacaciones', 'field' => 'vacation_type.name', 'key' => ':vacation_type_name'],
                                ['label' => 'Periodo', 'field' => 'period', 'key' => ':period'],
                                ['label' => 'Días', 'field' => 'days', 'key' => ':days'],
                                ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                            ]
                        ]
                    ]
                ],
            ],
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $vacationType = new VacationType();
            $vacationType->name = $subitem->name;
            $vacationType->description = $subitem->description;
            $vacationType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Cesantías';
        $item->description = 'Clasificación de las modalidades de cesantías aplicables a los trabajadores, que definen la forma de liquidación, consignación y manejo de este beneficio social conforme a la normativa laboral vigente y las políticas internas de la organización. Estas categorías permiten una gestión clara, controlada y trazable de las cesantías, garantizando el cumplimiento legal y una adecuada administración de los recursos del trabajador.';
        $item->settings = (object) [
            'variables' => [
                [
                    'label' => 'Persona',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Datos personales',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                            ]
                        ],
                        [
                            'title' => 'Datos de identificación',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                            ]
                        ],
                        [
                            'title' => 'Lugar y fecha de nacimiento',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                            ]
                        ],
                        [
                            'title' => 'Datos de contacto',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                            ]
                        ],
                        [
                            'title' => 'Residencia actual',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                            ]
                        ]
                    ]
                ],
                [
                    'label' => 'Retiro de cesantías',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Información',
                            'path'  => '',
                            'fields' => [
                                ['label' => 'Tipo de retiro de cesantías', 'field' => 'severance_type.name', 'key' => ':severance_type_name'],
                                ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                            ]
                        ]
                    ]
                ],
            ],
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $severanceType = new SeveranceType();
            $severanceType->name = $subitem->name;
            $severanceType->description = $subitem->description;
            $severanceType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Incapacidades';
        $item->description = 'Clasificación de las modalidades de incapacidades médicas otorgadas a los trabajadores, que determinan el origen, duración y condiciones de reconocimiento de la incapacidad conforme a la normativa laboral y de seguridad social vigente. Estas categorías permiten una gestión clara, controlada y trazable de los periodos de incapacidad, garantizando el cumplimiento legal y la correcta administración de los derechos y prestaciones del trabajador.';
        $item->settings = (object) [
            'variables' => [
                [
                    'label' => 'Persona',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Datos personales',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                            ]
                        ],
                        [
                            'title' => 'Datos de identificación',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                            ]
                        ],
                        [
                            'title' => 'Lugar y fecha de nacimiento',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                            ]
                        ],
                        [
                            'title' => 'Datos de contacto',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                            ]
                        ],
                        [
                            'title' => 'Residencia actual',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                            ]
                        ]
                    ]
                ],
                [
                    'label' => 'Incapacidad',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Información',
                            'path'  => '',
                            'fields' => [
                                ['label' => 'Tipo de incapacidad', 'field' => 'disability_type.name', 'key' => ':disability_type_name'],
                                ['label' => 'CIE codigo', 'field' => 'cie_code.settings.code', 'key' => ':cie_code_code'],
                                ['label' => 'CIE', 'field' => 'cie_code.name', 'key' => ':cie_code_name'],
                                ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                                ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                            ]
                        ]
                    ]
                ],
            ],
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $disabilityType = new DisabilityType();
            $disabilityType->name = $subitem->name;
            $disabilityType->description = $subitem->description;
            $disabilityType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Licencias';
        $item->description = 'Clasificación de las licencias laborales otorgadas a los trabajadores para ausentarse temporalmente de sus funciones por motivos personales, familiares, legales o institucionales, conforme a la normativa laboral vigente y las políticas internas de la organización. Estas categorías permiten una gestión ordenada, controlada y trazable de las licencias, garantizando el cumplimiento legal y la adecuada administración del tiempo laboral.';
        $item->settings = (object) [
            'variables' => [
                [
                    'label' => 'Persona',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Datos personales',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                            ]
                        ],
                        [
                            'title' => 'Datos de identificación',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                            ]
                        ],
                        [
                            'title' => 'Lugar y fecha de nacimiento',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                            ]
                        ],
                        [
                            'title' => 'Datos de contacto',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                            ]
                        ],
                        [
                            'title' => 'Residencia actual',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                            ]
                        ]
                    ]
                ],
                [
                    'label' => 'Licencia',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Información',
                            'path'  => '',
                            'fields' => [
                                ['label' => 'Tipo de licencia', 'field' => 'license_type.name', 'key' => ':license_type_name'],
                                ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                                ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                            ]
                        ]
                    ]
                ],
            ],
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $licenseType = new LicenseType();
            $licenseType->name = $subitem->name;
            $licenseType->description = $subitem->description;
            $licenseType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Certificados';
        $item->description = 'Corresponde a la clasificación de los distintos certificados laborales y administrativos que la organización puede emitir para sus trabajadores, según su vínculo contractual y la finalidad del documento.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Certificado laboral',
                'description' => 'Documento que acredita la relación laboral entre el trabajador y la empresa, indicando información como cargo, tipo de contrato y periodo de vinculación.'
            ],
            (object) [
                'name' => 'Certificado de ingresos y retenciones',
                'description' => 'Documento que certifica los ingresos percibidos por el trabajador y las retenciones efectuadas durante un periodo fiscal determinado, utilizado para trámites tributarios, financieros y legales.'
            ],
        ];

        foreach ($subitems as $subitem) {
            $certificateType = new CertificateType();
            $certificateType->name = $subitem->name;
            $certificateType->description = $subitem->description;
            $certificateType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Renuncias';
        $item->description = 'Clasificación de las diferentes modalidades de renuncia voluntaria del trabajador, según las condiciones y circunstancias en que se presenta la finalización del vínculo laboral.';
        $item->settings = (object) [
            'variables' => [
                [
                    'label' => 'Persona',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Datos personales',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                            ]
                        ],
                        [
                            'title' => 'Datos de identificación',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                            ]
                        ],
                        [
                            'title' => 'Lugar y fecha de nacimiento',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                            ]
                        ],
                        [
                            'title' => 'Datos de contacto',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                            ]
                        ],
                        [
                            'title' => 'Residencia actual',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                            ]
                        ]
                    ]
                ],
                [
                    'label' => 'Renuncia',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Información',
                            'path'  => '',
                            'fields' => [
                                ['label' => 'Tipo de renuncia', 'field' => 'resignation_type.name', 'key' => ':resignation_type_name'],
                                ['label' => 'Fecha', 'field' => 'date', 'key' => ':date'],
                                ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                            ]
                        ]
                    ]
                ],
            ],
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $resignationType = new ResignationType();
            $resignationType->name = $subitem->name;
            $resignationType->description = $subitem->description;
            $resignationType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tipos de Terminación de contrato';
        $item->description = 'Clasificación de las modalidades mediante las cuales el empleador da por terminado el contrato de trabajo, de acuerdo con la normativa laboral vigente.';
        $item->settings = (object) [
            'variables' => [
                [
                    'label' => 'Persona',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Datos personales',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                            ]
                        ],
                        [
                            'title' => 'Datos de identificación',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                            ]
                        ],
                        [
                            'title' => 'Lugar y fecha de nacimiento',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                            ]
                        ],
                        [
                            'title' => 'Datos de contacto',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                            ]
                        ],
                        [
                            'title' => 'Residencia actual',
                            'path'  => 'person',
                            'fields' => [
                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                            ]
                        ]
                    ]
                ],
                [
                    'label' => 'Terminación',
                    'path'  => '',
                    'groups' => [
                        [
                            'title' => 'Información',
                            'path'  => '',
                            'fields' => [
                                ['label' => 'Tipo de terminación', 'field' => 'model.name', 'class' => TerminationType::class, 'key' => ':termination_type_name'],
                                ['label' => 'Motivo', 'field' => 'model.name', 'class' => Reason::class, 'key' => ':reason_name'],
                                ['label' => 'Fecha', 'field' => 'date', 'key' => ':date'],
                                ['label' => 'Justificación', 'field' => 'reason', 'key' => ':reason'],
                                ['label' => 'Decisión', 'field' => 'decision', 'key' => ':decision'],
                            ]
                        ]
                    ]
                ],
            ],
        ];
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $terminationType = new TerminationType();
            $terminationType->name = $subitem->name;
            $terminationType->description = $subitem->description;
            $terminationType->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Capítulos CIE';
        $item->description = 'Corresponde a la clasificación principal de la Clasificación Internacional de Enfermedades (CIE), que agrupa las enfermedades según sistemas, órganos o condiciones generales.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $cieChapter = new CIEChapter();
            $cieChapter->name = $subitem->name;
            $cieChapter->description = $subitem->description;
            $cieChapter->settings = $subitem->settings;
            $cieChapter->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Grupos CIE';
        $item->description = 'Subclasificación de los capítulos CIE que agrupa enfermedades relacionadas por características clínicas o etiológicas comunes.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $cieGroup = new CIEGroup();
            $cieGroup->name = $subitem->name;
            $cieGroup->description = $subitem->description;
            $cieGroup->save();

            $cieGroup->cie_chapter()->sync($subitem->cie_chapter);
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Subgrupos CIE';
        $item->description = 'Nivel de clasificación que organiza de manera más específica los grupos CIE, permitiendo una identificación más detallada de las enfermedades.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $cieSubgroup = new CIESubgroup();
            $cieSubgroup->name = $subitem->name;
            $cieSubgroup->description = $subitem->description;
            $cieSubgroup->settings = $subitem->settings;
            $cieSubgroup->save();

            $cieSubgroup->cie_group()->sync($subitem->cie_group);
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Códigos CIE';
        $item->description = 'Código alfanumérico oficial de la Clasificación Internacional de Enfermedades que identifica de forma única una enfermedad, condición o diagnóstico.';
        $item->save();

        $subitems = [];

        foreach ($subitems as $subitem) {
            $cieCode = new CIECode();
            $cieCode->name = $subitem->name;
            $cieCode->description = $subitem->description;
            $cieCode->settings = $subitem->settings;
            $cieCode->save();

            $cieCode->cie_subgroup()->sync($subitem->cie_subgroup);
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Parentescos';
        $item->description = 'Define los tipos de relación familiar o legal que existen entre una persona y sus contactos, como padres, hijos, cónyuge u otros vínculos, utilizados para identificar relaciones personales y contactos de emergencia.';
        $item->save();

        $subitems = [
            (object) [
                'name' => 'Padre',
                'description' => 'Relación de parentesco que identifica al progenitor masculino de una persona.'
            ],
            (object) [
                'name' => 'Madre',
                'description' => 'Relación de parentesco que identifica a la progenitora femenina de una persona.'
            ],
            (object) [
                'name' => 'Hijo',
                'description' => 'Relación de parentesco que identifica al descendiente directo de una persona.'
            ],
            (object) [
                'name' => 'Hija',
                'description' => 'Relación de parentesco que identifica a la descendiente directa de una persona.'
            ],
            (object) [
                'name' => 'Cónyuge',
                'description' => 'Relación legal o civil que une a dos personas mediante matrimonio.'
            ],
            (object) [
                'name' => 'Pareja',
                'description' => 'Relación afectiva estable entre dos personas, reconocida o no legalmente.'
            ],
            (object) [
                'name' => 'Hermano',
                'description' => 'Relación de parentesco entre personas que comparten uno o ambos progenitores.'
            ],
            (object) [
                'name' => 'Hermana',
                'description' => 'Relación de parentesco entre personas que comparten uno o ambos progenitores.'
            ],
            (object) [
                'name' => 'Abuelo',
                'description' => 'Relación de parentesco que identifica al padre del padre o de la madre.'
            ],
            (object) [
                'name' => 'Abuela',
                'description' => 'Relación de parentesco que identifica a la madre del padre o de la madre.'
            ],
            (object) [
                'name' => 'Tío',
                'description' => 'Relación de parentesco que identifica al hermano del padre o de la madre.'
            ],
            (object) [
                'name' => 'Tía',
                'description' => 'Relación de parentesco que identifica a la hermana del padre o de la madre.'
            ],
            (object) [
                'name' => 'Primo',
                'description' => 'Relación de parentesco entre personas que comparten abuelos.'
            ],
            (object) [
                'name' => 'Prima',
                'description' => 'Relación de parentesco entre personas que comparten abuelos.'
            ],
            (object) [
                'name' => 'Otro',
                'description' => 'Relación de parentesco no contemplada específicamente dentro del listado.'
            ],
        ];

        foreach ($subitems as $subitem) {
            $relationship = new Relationship();
            $relationship->name = $subitem->name;
            $relationship->description = $subitem->description;
            $relationship->save();
        }

        //=======================================================================================================================

        $item = new Item();
        $item->name = 'Tallas';
        $item->description = 'Catálogo de tallas para prendas y dotación.';
        $item->save();

        $subitems = [
            (object) ['name' => '04', 'description' => null],
            (object) ['name' => '06', 'description' => null],
            (object) ['name' => '08', 'description' => null],
            (object) ['name' => '10', 'description' => null],
            (object) ['name' => '12', 'description' => null],
            (object) ['name' => '14', 'description' => null],
            (object) ['name' => '16', 'description' => null],
            (object) ['name' => '18', 'description' => null],
            (object) ['name' => '20', 'description' => null],
            (object) ['name' => '22', 'description' => null],
            (object) ['name' => '28', 'description' => null],
            (object) ['name' => '30', 'description' => null],
            (object) ['name' => '32', 'description' => null],
            (object) ['name' => '34', 'description' => null],
            (object) ['name' => '36', 'description' => null],
            (object) ['name' => '38', 'description' => null],
            (object) ['name' => '40', 'description' => null],
            (object) ['name' => '42', 'description' => null],
            (object) ['name' => 'XXXS', 'description' => null],
            (object) ['name' => 'XXS', 'description' => null],
            (object) ['name' => 'XS', 'description' => null],
            (object) ['name' => 'S', 'description' => null],
            (object) ['name' => 'M', 'description' => null],
            (object) ['name' => 'L', 'description' => null],
            (object) ['name' => 'XL', 'description' => null],
            (object) ['name' => 'XXL', 'description' => null],
            (object) ['name' => 'XXXL', 'description' => null],
        ];

        foreach ($subitems as $subitem) {
            $size = new Size();
            $size->name = $subitem->name;
            $size->description = $subitem->description;
            $size->save();
        }

        //=======================================================================================================================

        /*$datos = [
            (object) [
                'name' => 'Tipo de Contrato',
                'description' => 'Listado de tipos de contrato disponibles.',
                'subitems' => [
                    (object) ['name' => 'Término Fijo', 'description' => 'Contrato con fecha de finalización establecida.' ],
                    (object) ['name' => 'Término Indefinido', 'description' => 'Contrato sin fecha de terminación definida.'],
                    (object) ['name' => 'Prestación de Servicios', 'description' => 'Contrato por honorarios para servicios específicos.'],
                    (object) ['name' => 'Aprendizaje', 'description' => 'Contrato para formación práctica de estudiantes.'],
                    (object) ['name' => 'Obra o Labor', 'description' => 'Contrato asociado a la realización de una obra o actividad específica.']
                ],
            ],
            (object) [
                'name' => 'Jornada Laboral',
                'description' => 'Tipos de jornada laboral.',
                'subitems' => [
                    (object) ['name' => 'Jornada Completa', 'description' => 'Trabajo a tiempo completo.'],
                    (object) ['name' => 'Medio Tiempo', 'description' => 'Trabajo a medio tiempo.'],
                    (object) ['name' => 'Turnos Rotativos', 'description' => 'Trabajo con turnos rotativos.']
                ],
            ],
            (object) [
                'name' => 'Modalidad de Trabajo',
                'description' => 'Modalidades de trabajo disponibles.',
                'subitems' => [
                    (object) ['name' => 'Presencial', 'description' => 'Trabajo realizado en oficina o sitio físico.'],
                    (object) ['name' => 'Virtual', 'description' => 'Trabajo realizado de manera remota.'],
                    (object) ['name' => 'Híbrido', 'description' => 'Trabajo combinado entre presencial y virtual.']
                ]
            ],
            (object) [
                'name' => 'Tipo de Documento',
                'description' => 'Tipos de documento válidos.',
                'subitems' => [
                    (object) [
                        'name' => 'Cédula de Ciudadanía',
                        'description' => 'Documento para ciudadanos.',
                        'settings' => (object) [
                            'abreviature' => 'CC',
                            'validations' => [
                                (object) [
                                    'id' => 1,
                                    'country_id' => 48,
                                    'regex' => '/^[0-9]{6,10}$/',
                                    'message' => 'Debe tener entre 6 y 10 dígitos numéricos.',
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'deleted_at' => null
                                ]
                            ]
                        ]
                    ],
                    (object) [
                        'name' => 'Cédula de Extranjería',
                        'description' => 'Documento para extranjeros residentes.',
                        'settings' => (object) [
                            'abreviature' => 'CE',
                            'validations' => [
                                (object) [
                                    'id' => 1,
                                    'country_id' => 48,
                                    'regex' => '/^[A-Z0-9]{5,12}$/',
                                    'message' => 'Debe ser alfanumérico de 5 a 12 caracteres.',
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'deleted_at' => null
                                ]
                            ]
                        ]
                    ],
                    (object) [
                        'name' => 'Pasaporte',
                        'description' => 'Documento internacional de identificación.',
                        'settings' => (object) [
                            'abreviature' => 'PA',
                            'validations' => [
                                (object) [
                                    'id' => 1,
                                    'country_id' => 48,
                                    'regex' => '/^[A-Z]{2}[0-9]{6}$/',
                                    'message' => 'Debe tener 2 letras seguidas de 6 números.',
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'deleted_at' => null
                                ]
                            ]
                        ]
                    ],
                    (object) [
                        'name' => 'Tarjeta de Identidad',
                        'description' => 'Documento para menores de edad.',
                        'settings' => (object) [
                            'abreviature' => 'TI',
                            'validations' => [
                                (object) [
                                    'id' => 1,
                                    'country_id' => 48,
                                    'regex' => '/^[0-9]{6,8}$/',
                                    'message' => 'Debe tener entre 6 y 8 dígitos numéricos.',
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'deleted_at' => null
                                ]
                            ]
                        ]
                    ],
                    (object) [
                        'name' => 'Permiso Especial de Permanencia',
                        'description' => 'Documento especial para extranjeros.',
                        'settings' => (object) [
                            'abreviature' => 'PEP',
                            'validations' => [
                                (object) [
                                    'id' => 1,
                                    'country_id' => 48,
                                    'regex' => '/^[A-Z0-9]{6,12}$/',
                                    'message' => 'Debe ser alfanumérico de 6 a 12 caracteres.',
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'deleted_at' => null
                                ]
                            ]
                        ]
                    ],
                    (object) [
                        'name' => 'Permiso por Protección Temporal',
                        'description' => 'Documento temporal para protección especial de extranjeros.',
                        'settings' => (object) [
                            'abreviature' => 'PPT',
                            'validations' => [
                                (object) [
                                    'id' => 1,
                                    'country_id' => 48,
                                    'regex' => '/^[A-Z0-9]{6,12}$/',
                                    'message' => 'Debe ser alfanumérico de 6 a 12 caracteres.',
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'deleted_at' => null
                                ]
                            ]
                        ]
                    ],
                    (object) [
                        'name' => 'NIT',
                        'description' => 'Número de Identificación Tributaria.',
                        'settings' => (object) [
                            'abreviature' => 'NIT',
                            'validations' => [
                                (object) [
                                    'id' => 1,
                                    'country_id' => 48,
                                    'regex' => '/^[0-9]{9,15}-[0-9]$/',
                                    'message' => 'Debe tener entre 9 y 15 dígitos numéricos seguidos de un guion y un dígito identificador.',
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'deleted_at' => null
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Tipo de Persona',
                'description' => 'Clasificación que identifica la naturaleza jurídica de una persona dentro del sistema.',
                'subitems' => [
                    (object) ['name' => 'Natural', 'description' => 'Persona natural.'],
                    (object) ['name' => 'Jurídica', 'description' => 'Persona jurídica.']
                ]
            ],
            (object) [
                'name' => 'Tipo de Habilidades',
                'description' => 'Clasificación de habilidades.',
                'subitems' => [
                    (object) [
                        'name' => 'Blanda',
                        'description' => 'Habilidades sociales y actitudinales.',
                        'settings' => (object) [
                            'regex' => '/^[1-5]$/',
                            'message' => 'Seleccione una calificación de 1 a 5 estrellas.'
                        ]
                    ],
                    (object) [
                        'name' => 'Dura',
                        'description' => 'Habilidades técnicas y específicas.',
                        'settings' => (object) [
                            'regex' => '/^(100|[1-9][0-9]?|0?[1-9])$/',
                            'message' => 'Ingrese solo números enteros entre 1 y 100.'
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Estado de la formación',
                'description' => 'Estados que describen la situación de la formación académica.',
                'subitems' => [
                    (object) ['name' => 'No Iniciado', 'description' => 'La formación aún no ha comenzado.'],
                    (object) ['name' => 'En Curso', 'description' => 'La formación está actualmente en desarrollo.'],
                    (object) ['name' => 'Suspendido', 'description' => 'La formación está temporalmente suspendida.'],
                    (object) ['name' => 'Abandonado', 'description' => 'La formación fue abandonada antes de finalizar.'],
                    (object) ['name' => 'Finalizado', 'description' => 'La formación fue completada satisfactoriamente.']
                ]
            ],
            (object) [
                'name' => 'Nivel Académico',
                'description' => 'Grados de formación académica.',
                'subitems' => [
                    (object) ['name' => 'Básica Primaria', 'description' => 'Primera etapa de formación escolar.'],
                    (object) ['name' => 'Básica Secundaria', 'description' => 'Formación escolar secundaria.'],
                    (object) ['name' => 'Media', 'description' => 'Educación media (bachillerato).'],
                    (object) ['name' => 'Técnico', 'description' => 'Formación técnica.'],
                    (object) ['name' => 'Tecnólogo', 'description' => 'Formación tecnológica.'],
                    (object) ['name' => 'Profesional', 'description' => 'Formación universitaria.'],
                    (object) ['name' => 'Especialización', 'description' => 'Estudios de especialización.'],
                    (object) ['name' => 'Maestría', 'description' => 'Estudios de maestría.'],
                    (object) ['name' => 'Doctorado', 'description' => 'Estudios de doctorado.'],
                    (object) ['name' => 'Curso', 'description' => 'Estudios cortos y específicos sobre una temática.'],
                    (object) ['name' => 'Diplomado', 'description' => 'Programa académico de corta duración sobre un área específica.'],
                    (object) ['name' => 'Certificación', 'description' => 'Reconocimiento formal de competencias adquiridas.'],
                    (object) ['name' => 'Seminario', 'description' => 'Evento académico breve sobre temas concretos.'],
                    (object) ['name' => 'Taller', 'description' => 'Espacio práctico de aprendizaje en un área específica.'],
                ]
            ],
            (object) [
                'name' => 'Sexo',
                'description' => 'Sexo biológico.',
                'subitems' => [
                    (object) ['name' => 'Masculino', 'description' => 'Sexo masculino.'],
                    (object) ['name' => 'Femenino', 'description' => 'Sexo femenino.']
                ]
            ],
            (object) [
                'name' => 'Género',
                'description' => 'Identidad de género.',
                'subitems' => [
                    (object) ['name' => 'Hombre', 'description' => 'Identidad masculina.'],
                    (object) ['name' => 'Mujer', 'description' => 'Identidad femenina.'],
                    (object) ['name' => 'No Binario', 'description' => 'No se identifica como hombre ni mujer.'],
                    (object) ['name' => 'Prefiere no decirlo', 'description' => 'Prefiere no especificar.'],
                    (object) ['name' => 'Otro', 'description' => 'Otra identidad de género.']
                ]
            ],
            (object) [
                'name' => 'Tipo de Sangre',
                'description' => 'Tipos de sangre y factores Rh.',
                'subitems' => [
                    (object) ['name' => 'A+', 'description' => 'Tipo de sangre A positivo.'],
                    (object) ['name' => 'A-', 'description' => 'Tipo de sangre A negativo.'],
                    (object) ['name' => 'B+', 'description' => 'Tipo de sangre B positivo.'],
                    (object) ['name' => 'B-', 'description' => 'Tipo de sangre B negativo.'],
                    (object) ['name' => 'AB+', 'description' => 'Tipo de sangre AB positivo.'],
                    (object) ['name' => 'AB-', 'description' => 'Tipo de sangre AB negativo.'],
                    (object) ['name' => 'O+', 'description' => 'Tipo de sangre O positivo.'],
                    (object) ['name' => 'O-', 'description' => 'Tipo de sangre O negativo.']
                ]
            ],
            (object) [
                'name' => 'Estado Civil',
                'description' => 'Estados civiles reconocidos.',
                'subitems' => [
                    (object) ['name' => 'Soltero', 'description' => 'Persona sin vínculo matrimonial.'],
                    (object) ['name' => 'Casado', 'description' => 'Persona unida en matrimonio.'],
                    (object) ['name' => 'Unión Libre', 'description' => 'Persona en unión marital de hecho.'],
                    (object) ['name' => 'Divorciado', 'description' => 'Persona con matrimonio disuelto legalmente.'],
                    (object) ['name' => 'Viudo', 'description' => 'Persona cuyo cónyuge ha fallecido.']
                ]
            ],
            (object) [
                'name' => 'Categoría Perfil',
                'description' => 'Clasificación del perfil laboral.',
                'subitems' => [
                    (object) ['name' => 'Operativo', 'description' => 'Perfil operativo.'],
                    (object) ['name' => 'Administrativo', 'description' => 'Perfil administrativo.']
                ]
            ],
            (object) [
                'name' => 'Bancos',
                'description' => 'Entidades bancarias.',
                'subitems' => [
                    (object) ['name' => 'Banco de Bogotá', 'description' => 'Banco de Bogotá.'],
                    (object) ['name' => 'Banco Popular', 'description' => 'Banco Popular.'],
                    (object) ['name' => 'Itaú', 'description' => 'Itaú.'],
                    (object) ['name' => 'Bancolombia', 'description' => 'Bancolombia.'],
                    (object) ['name' => 'Citibank', 'description' => 'Citibank.'],
                    (object) ['name' => 'Banco GNB Sudameris', 'description' => 'Banco GNB Sudameris.'],
                    (object) ['name' => 'BBVA', 'description' => 'BBVA.'],
                    (object) ['name' => 'Banco de Occidente', 'description' => 'Banco de Occidente.'],
                    (object) ['name' => 'Banco Caja Social', 'description' => 'Banco Caja Social.'],
                    (object) ['name' => 'Banco Davivienda', 'description' => 'Banco Davivienda.'],
                    (object) ['name' => 'Scotiabank Colpatria', 'description' => 'Scotiabank Colpatria.'],
                    (object) ['name' => 'Banco Agrario de Colombia', 'description' => 'Banco Agrario de Colombia.'],
                    (object) ['name' => 'Banco AV Villas', 'description' => 'Banco AV Villas.'],
                    (object) ['name' => 'Bancien', 'description' => 'Bancien o BAN100.'],
                    (object) ['name' => 'Bancamía', 'description' => 'Banco de las Microfinanzas Bancamía.'],
                    (object) ['name' => 'Banco W', 'description' => 'Banco W.'],
                    (object) ['name' => 'Banco Coomeva', 'description' => 'Banco Coomeva.'],
                    (object) ['name' => 'Banco Finandina', 'description' => 'Banco Finandina.'],
                    (object) ['name' => 'Banco Falabella', 'description' => 'Banco Falabella.'],
                    (object) ['name' => 'Banco Pichincha', 'description' => 'Banco Pichincha.'],
                    (object) ['name' => 'Coopcentral', 'description' => 'Banco Cooperativo Coopcentral.'],
                    (object) ['name' => 'Banco Santander', 'description' => 'Banco Santander de Negocios Colombia.'],
                    (object) ['name' => 'Banco Mundo Mujer', 'description' => 'Banco Mundo Mujer.'],
                    (object) ['name' => 'Mibanco', 'description' => 'Banco de la Microempresa Mibanco.'],
                    (object) ['name' => 'Banco Serfinanza', 'description' => 'Banco Serfinanza.'],
                    (object) ['name' => 'Banco J.P. Morgan', 'description' => 'Banco J.P. Morgan Colombia.'],
                    (object) ['name' => 'Lulo Bank', 'description' => 'Lulo Bank.'],
                    (object) ['name' => 'Banco BTG Pactual', 'description' => 'Banco BTG Pactual Colombia.'],
                    (object) ['name' => 'Banco Unión', 'description' => 'Banco Unión.'],
                    (object) ['name' => 'Banco Contactar', 'description' => 'Banco Contactar.']
                ]
            ],
            (object) [
                'name' => 'Tipo de Cuenta Bancaria',
                'description' => 'Tipos de cuenta bancaria disponibles.',
                'subitems' => [
                    (object) ['name' => 'Ahorros', 'description' => 'Cuenta de ahorros.'],
                    (object) ['name' => 'Corriente', 'description' => 'Cuenta corriente.']
                ]
            ],
            (object) [
                'name' => 'Tipos de Archivos',
                'description' => 'Clasificación estandarizada de archivos según su naturaleza, uso y propósito dentro de procesos administrativos, legales o documentales.',
                'subitems' => [
                    (object) [
                        'name' => 'Identificación',
                        'description' => 'Documentos que validan la identidad de personas o entidades (cédula, pasaporte, RUT, cámara de comercio, etc.).'
                    ],
                    (object) [
                        'name' => 'Imagen Corporativa',
                        'description' => 'Elementos visuales que representan a la organización (logos, manual de marca, tarjetas, firmas digitales, plantillas gráficas).'
                    ],
                    (object) [
                        'name' => 'Plantillas Contractuales',
                        'description' => 'Modelos predefinidos de documentos legales y de vinculación usados en procesos corporativos.'
                    ],
                    (object) [
                        'name' => 'Documentos Académicos o Laborales',
                        'description' => 'Títulos, certificados de cursos, certificaciones técnicas, evidencia de competencias.'
                    ],
                    (object) [
                        'name' => 'Documentos Personales',
                        'description' => 'Documentos que identifican o describen información personal, historial o soporte individual.'
                    ],
                    (object) [
                        'name' => 'Producción de Contenido',
                        'description' => 'Archivos multimedia o escritos generados por el usuario (videos, imágenes, audios).'
                    ]
                ]
            ],
            (object) [
                'name' => 'Subtipos de Archivos',
                'description' => 'Clasificación específica de archivos según su contenido y función.',
                'subitems' => [
                    // Identificación
                    (object) ['name' => 'Documento de Identidad', 'description' => 'Cédula, pasaporte, licencia u otro documento identificador.'],
                    (object) ['name' => 'Identificación Tributaria o Fiscal', 'description' => 'Número o certificado oficial usado para fines tributarios (ej. NIT, VAT, RFC, CUIT).'],
                    (object) ['name' => 'Cámara de Comercio', 'description' => 'Certificado de existencia y representación legal de una empresa.'],
                    (object) ['name' => 'Poder o Representación Legal', 'description' => 'Documento que acredita facultades legales sobre una entidad.'],

                    // Imagen Corporativa
                    (object) ['name' => 'Logo', 'description' => 'Archivo del logotipo institucional en formatos editables o exportados.'],
                    (object) ['name' => 'Imagen Banner', 'description' => 'Imagen institucional usada en web, redes o presentaciones.'],
                    (object) ['name' => 'Imagen Misión', 'description' => 'Gráfico que representa la razón de ser de la organización.'],
                    (object) ['name' => 'Imagen Visión', 'description' => 'Gráfico que representa la proyección o meta futura de la organización.'],
                    (object) ['name' => 'Imagen Sobre Nosotros', 'description' => 'Composición visual que representa presentación institucional.'],
                    (object) ['name' => 'Imagen de Valores Corporativos', 'description' => 'Diseños que ilustran declaraciones de valores organizacionales.'],

                    // Plantillas Contractuales
                    (object) [
                        'name' => 'Plantilla de Contratación Directa',
                        'description' => 'Modelo base para vinculación formal bajo relación laboral.',
                        'settings' => (object) [
                            'variables' => [
                                [
                                    'label' => 'Empleador',
                                    'path'  => 'application',
                                    'groups' => [
                                        [
                                            'title' => 'Empresa',
                                            'path'  => 'vacant.headquarter.entity.enterprise',
                                            'fields' => [
                                                ['label' => 'Nombre', 'field'  => 'name', 'key' => ':enterprise_name'],
                                                ['label' => 'Descripcion', 'field'  => 'description', 'key' => ':enterprise_description']
                                            ]
                                        ],
                                        [
                                            'title' => 'Entidad',
                                            'path'  => 'vacant.headquarter.entity',
                                            'fields' => [
                                                ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_entity_type'],
                                                ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                                ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                            ]
                                        ],
                                        [
                                            'title' => 'Representante legal',
                                            'path'  => 'vacant.headquarter.entity.person',
                                            'fields' => [
                                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':legal_representative_first_name'],
                                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':legal_representative_second_name'],
                                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':legal_representative_first_last_name'],
                                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':legal_representative_second_last_name'],
                                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':legal_representative_document_type'],
                                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':legal_representative_document_number']
                                            ]
                                        ],
                                        [
                                            'title' => 'Sede',
                                            'path'  => 'vacant.headquarter',
                                            'fields' => [
                                                ['label' => 'Nombre', 'field' => 'name', 'key' => ':headquarter_name'],
                                                ['label' => 'Departamento', 'field' => 'city.department.name', 'key' => ':headquarter_departament'],
                                                ['label' => 'Ciudad',  'field' => 'city.name', 'key' => ':headquarter_city'],
                                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address'],
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Candidato',
                                    'path'  => 'application',
                                    'groups' => [
                                        [
                                            'title' => 'Datos personales',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                            ]
                                        ],
                                        [
                                            'title' => 'Datos de identificación',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                            ]
                                        ],
                                        [
                                            'title' => 'Lugar y fecha de nacimiento',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                            ]
                                        ],
                                        [
                                            'title' => 'Datos de contacto',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                            ]
                                        ],
                                        [
                                            'title' => 'Residencia actual',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Contrato',
                                    'groups' => [
                                        [
                                            'title' => 'Información relevante',
                                            'path'  => 'contract',
                                            'fields' => [
                                                ['label' => 'Cargo', 'field' => 'position.name', 'key' => ':contract_position'],
                                                ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_contract_type'],
                                                ['label' => 'Periodo de pago', 'field' => 'payment_period.name', 'key' => ':contract_payment_period'],
                                                ['label' => 'Salario', 'field' => 'salary', 'key' => ':contract_salary'],
                                                ['label' => 'Fecha inicio labores', 'field' => 'start_date', 'key' => ':contract_start_date'],
                                                ['label' => 'Fecha fin labores', 'field' => 'end_date', 'key' => ':contract_end_date']
                                            ]
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ],
                    (object) [
                        'name' => 'Plantilla de Vinculación por Prestación de Servicios',
                        'description' => 'Modelo para acuerdos con contratistas externos sin relación laboral.',
                        'settings' => (object) [
                            'variables' => [
                                [
                                    'label' => 'Empleador',
                                    'path'  => 'application',
                                    'groups' => [
                                        [
                                            'title' => 'Empresa',
                                            'path'  => 'vacant.headquarter.entity.enterprise',
                                            'fields' => [
                                                ['label' => 'Nombre', 'field'  => 'name', 'key' => ':enterprise_name'],
                                                ['label' => 'Descripcion', 'field'  => 'description', 'key' => ':enterprise_description']
                                            ]
                                        ],
                                        [
                                            'title' => 'Entidad',
                                            'path'  => 'vacant.headquarter.entity',
                                            'fields' => [
                                                ['label' => 'Nombre', 'field' => 'name', 'key' => ':entity_entity_type'],
                                                ['label' => 'Tipo', 'field' => 'entity_type.settings.abreviature', 'key' => ':entity_entity_type'],
                                                ['label' => 'Pais', 'field' => 'country.name', 'key' => ':entity_country'],
                                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':entity_document_type'],
                                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':entity_document_number']
                                            ]
                                        ],
                                        [
                                            'title' => 'Representante legal',
                                            'path'  => 'vacant.headquarter.entity.person',
                                            'fields' => [
                                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':legal_representative_first_name'],
                                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':legal_representative_second_name'],
                                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':legal_representative_first_last_name'],
                                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':legal_representative_second_last_name'],
                                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':legal_representative_document_type'],
                                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':legal_representative_document_number']
                                            ]
                                        ],
                                        [
                                            'title' => 'Sede',
                                            'path'  => 'vacant.headquarter',
                                            'fields' => [
                                                ['label' => 'Nombre', 'field' => 'name', 'key' => ':headquarter_name'],
                                                ['label' => 'Departamento', 'field' => 'city.department.name', 'key' => ':headquarter_departament'],
                                                ['label' => 'Ciudad',  'field' => 'city.name', 'key' => ':headquarter_city'],
                                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':headquarter_address'],
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Candidato',
                                    'path'  => 'application',
                                    'groups' => [
                                        [
                                            'title' => 'Datos personales',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                            ]
                                        ],
                                        [
                                            'title' => 'Datos de identificación',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                            ]
                                        ],
                                        [
                                            'title' => 'Lugar y fecha de nacimiento',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                            ]
                                        ],
                                        [
                                            'title' => 'Datos de contacto',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                            ]
                                        ],
                                        [
                                            'title' => 'Residencia actual',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'Contrato',
                                    'groups' => [
                                        [
                                            'title' => 'Información relevante',
                                            'path'  => 'contract',
                                            'fields' => [
                                                ['label' => 'Cargo', 'field' => 'position.name', 'key' => ':contract_position'],
                                                ['label' => 'Tipo de contrato', 'field' => 'contract_type.name', 'key' => ':contract_contract_type'],
                                                ['label' => 'Periodo de pago', 'field' => 'payment_period.name', 'key' => ':contract_payment_period'],
                                                ['label' => 'Salario', 'field' => 'salary', 'key' => ':contract_salary'],
                                                ['label' => 'Fecha inicio labores', 'field' => 'start_date', 'key' => ':contract_start_date'],
                                                ['label' => 'Fecha fin labores', 'field' => 'end_date', 'key' => ':contract_end_date']
                                            ]
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ],
                    (object) ['name' => 'Acuerdo de Confidencialidad', 'description' => 'Plantilla para protección de información sensible (NDA).'],
                    (object) ['name' => 'Autorización de Tratamiento de Datos', 'description' => 'Documento para consentir uso de datos personales conforme a la ley.'],
                    (object) ['name' => 'Otro Sí / Modificación', 'description' => 'Formato para adicionar, modificar o extender condiciones del acuerdo original.'],
                    (object) ['name' => 'Certificación Laboral', 'description' => 'Formato para certificar vínculo, cargo y tiempo de servicio de una persona.'],

                    (object) ['name' => 'Acta de Inicio', 'description' => 'Documento que da formal inicio a la ejecución de un contrato.'],
                    (object) ['name' => 'Notificación de Finalización de Contrato', 'description' => 'Aviso formal de terminación de la relación contractual.'],
                    (object) ['name' => 'Acta de Terminación', 'description' => 'Documento que deja constancia de la finalización y entrega de obligaciones.'],

                    // Documentos Académicos o Laborales
                    (object) ['name' => 'Diploma', 'description' => 'Documento oficial que acredita un título obtenido.'],
                    (object) ['name' => 'Certificado Académico', 'description' => 'Constancia de aprobación de estudios o módulos.'],
                    (object) ['name' => 'Certificado Laboral', 'description' => 'Documento formal que certifica vínculo laboral y condiciones.' ],

                    // Documentos Personales
                    (object) ['name' => 'Hoja de Vida / Currículum Vitae', 'description' => 'Documento que resume trayectoria académica, laboral y habilidades del individuo.'],
                    (object) ['name' => 'Foto Personal', 'description' => 'Fotografía tipo documento o imagen personal para trámites.'],

                    // Producción de Contenido
                    (object) ['name' => 'Imagen', 'description' => 'Archivo visual.'],
                    (object) ['name' => 'Video', 'description' => 'Archivo audiovisual.'],
                    (object) ['name' => 'Audio', 'description' => 'Archivo de grabación sonora.'],

                    (object) ['name' => 'Anexos', 'description' => 'Documentos anexos correspondientes a la persona, que incluyen antecedentes, certificados, afiliaciones y demás soportes requeridos para los procesos administrativos o de validación.'],

                    (object) ['name' => 'Firma', 'description' => 'Documento digital que contiene la firma del representante o usuario autorizado.'],
                    (object) ['name' => 'PDF Contractual', 'description' => 'PDF generado automáticamente a partir de una plantilla contractual del sistema.'],
                    (object) ['name' => 'Cuenta de Cobro', 'description' => 'Documento presentado por un contratista o proveedor para solicitar el pago de servicios prestados, detallando valores, fechas y condiciones.'],
                    (object) ['name' => 'Informe de Actividades Realizadas', 'description' => 'Documento que describe las tareas, entregables y avances ejecutados durante un periodo específico, generalmente usado como soporte para pagos o seguimiento contractual.']
                ]
            ],
            (object) [
                'name' => 'Tipos de Formulario',
                'description' => 'Clasificación de los formularios disponibles.',
                'subitems' => [
                    (object) ['name' => 'Prueba Inicial', 'description' => 'Formulario aplicado al inicio del proceso de selección o diagnóstico.'],
                    (object) ['name' => 'Prueba Técnica', 'description' => 'Formulario para evaluar conocimientos o habilidades específicas según el rol.'],
                    (object) ['name' => 'Prueba Psicotécnica', 'description' => 'Formulario para aplicar pruebas de carácter psicométrico o de perfil conductual.'],
                    (object) ['name' => 'Encuesta', 'description' => 'Formulario para recolectar opiniones, impresiones o retroalimentación.'],
                    (object) ['name' => 'Información Complementaria', 'description' => 'Formulario para recopilar datos adicionales requeridos según el proceso.'],
                    (object) ['name' => 'Inducción', 'description' => 'Tipo de formulario utilizado para registrar, gestionar y documentar el proceso de inducción de los colaboradores, incluyendo la información necesaria para su integración a la organización.'],
                    (object) ['name' => 'Evaluación Video Entrevista', 'description' => 'Formulario para valorar la video entrevista.'],
                    (object) ['name' => 'Calificación de la Empresa', 'description' => 'Formulario para evaluar la percepción, satisfacción o reputación de la empresa.']
                ]
            ],
            (object) [
                'name' => 'Beneficios',
                'description' => 'Lista de beneficios disponibles.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Niveles de Idioma',
                'description' => 'Clasificación de los niveles de competencia en un idioma.',
                'subitems' => [
                    (object) [
                        'name' => 'Principiante',
                        'description' => 'Puede comprender y utilizar expresiones cotidianas de uso muy frecuente.',
                        'settings' => (object) [
                            'level' => 'A1'
                        ]
                    ],
                    (object) [
                        'name' => 'Básico',
                        'description' => 'Puede comprender frases y expresiones de uso frecuente relacionadas con áreas de experiencia que le son especialmente relevantes.',
                        'settings' => (object) [
                            'level' => 'A2'
                        ]
                    ],
                    (object) [
                        'name' => 'Intermedio',
                        'description' => 'Puede comprender los puntos principales de textos claros en lengua estándar.',
                        'settings' => (object) [
                            'level' => 'B1'
                        ]
                    ],
                    (object) [
                        'name' => 'Intermedio Alto',
                        'description' => 'Puede entender las ideas principales de textos complejos y puede interactuar con fluidez.',
                        'settings' => (object) [
                            'level' => 'B2'
                        ]
                    ],
                    (object) [
                        'name' => 'Avanzado',
                        'description' => 'Puede comprender una amplia variedad de textos largos y exigentes, y reconocer su significado implícito.',
                        'settings' => (object) [
                            'level' => 'C1'
                        ]
                    ],
                    (object) [
                        'name' => 'Experto',
                        'description' => 'Puede comprender con facilidad prácticamente todo lo que oye o lee.',
                        'settings' => (object) [
                            'level' => 'C2'
                        ]
                    ],
                    (object) [
                        'name' => 'Nativo',
                        'description' => 'Habla el idioma como lengua materna o con total dominio natural.',
                        'settings' => (object) [
                            'level' => 'N'
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Idiomas',
                'description' => 'Lista de idiomas con sus respectivos códigos.',
                'subitems' => [
                    (object) ['name' => 'Afar', 'description' => 'aa'],
                    (object) ['name' => 'Abjasio', 'description' => 'ab'],
                    (object) ['name' => 'Avéstico', 'description' => 'ae'],
                    (object) ['name' => 'Afrikáans', 'description' => 'af'],
                    (object) ['name' => 'Akan', 'description' => 'ak'],
                    (object) ['name' => 'Amárico', 'description' => 'am'],
                    (object) ['name' => 'Aragonés', 'description' => 'an'],
                    (object) ['name' => 'Árabe', 'description' => 'ar'],
                    (object) ['name' => 'Asamés', 'description' => 'as'],
                    (object) ['name' => 'Avar', 'description' => 'av'],
                    (object) ['name' => 'Aimara', 'description' => 'ay'],
                    (object) ['name' => 'Azerí', 'description' => 'az'],
                    (object) ['name' => 'Baskir', 'description' => 'ba'],
                    (object) ['name' => 'Bielorruso', 'description' => 'be'],
                    (object) ['name' => 'Búlgaro', 'description' => 'bg'],
                    (object) ['name' => 'Bihari', 'description' => 'bh'],
                    (object) ['name' => 'Bislama', 'description' => 'bi'],
                    (object) ['name' => 'Bambara', 'description' => 'bm'],
                    (object) ['name' => 'Bengalí', 'description' => 'bn'],
                    (object) ['name' => 'Tibetano', 'description' => 'bo'],
                    (object) ['name' => 'Bretón', 'description' => 'br'],
                    (object) ['name' => 'Bosnio', 'description' => 'bs'],
                    (object) ['name' => 'Catalán', 'description' => 'ca'],
                    (object) ['name' => 'Checheno', 'description' => 'ce'],
                    (object) ['name' => 'Chamorro', 'description' => 'ch'],
                    (object) ['name' => 'Corso', 'description' => 'co'],
                    (object) ['name' => 'Cree', 'description' => 'cr'],
                    (object) ['name' => 'Checo', 'description' => 'cs'],
                    (object) ['name' => 'Eslavo eclesiástico', 'description' => 'cu'],
                    (object) ['name' => 'Chuvasio', 'description' => 'cv'],
                    (object) ['name' => 'Galés', 'description' => 'cy'],
                    (object) ['name' => 'Danés', 'description' => 'da'],
                    (object) ['name' => 'Alemán', 'description' => 'de'],
                    (object) ['name' => 'Divehi', 'description' => 'dv'],
                    (object) ['name' => 'Dzongkha', 'description' => 'dz'],
                    (object) ['name' => 'Ewé', 'description' => 'ee'],
                    (object) ['name' => 'Griego', 'description' => 'el'],
                    (object) ['name' => 'Inglés', 'description' => 'en'],
                    (object) ['name' => 'Esperanto', 'description' => 'eo'],
                    (object) ['name' => 'Español', 'description' => 'es'],
                    (object) ['name' => 'Estonio', 'description' => 'et'],
                    (object) ['name' => 'Euskera', 'description' => 'eu'],
                    (object) ['name' => 'Persa', 'description' => 'fa'],
                    (object) ['name' => 'Fula', 'description' => 'ff'],
                    (object) ['name' => 'Finés', 'description' => 'fi'],
                    (object) ['name' => 'Fiyiano', 'description' => 'fj'],
                    (object) ['name' => 'Feroés', 'description' => 'fo'],
                    (object) ['name' => 'Francés', 'description' => 'fr'],
                    (object) ['name' => 'Frisón occidental', 'description' => 'fy'],
                    (object) ['name' => 'Irlandés', 'description' => 'ga'],
                    (object) ['name' => 'Gaélico escocés', 'description' => 'gd'],
                    (object) ['name' => 'Gallego', 'description' => 'gl'],
                    (object) ['name' => 'Guaraní', 'description' => 'gn'],
                    (object) ['name' => 'Gujarati', 'description' => 'gu'],
                    (object) ['name' => 'Manés', 'description' => 'gv'],
                    (object) ['name' => 'Hausa', 'description' => 'ha'],
                    (object) ['name' => 'Hebreo', 'description' => 'he'],
                    (object) ['name' => 'Hindi', 'description' => 'hi'],
                    (object) ['name' => 'Hiri motu', 'description' => 'ho'],
                    (object) ['name' => 'Croata', 'description' => 'hr'],
                    (object) ['name' => 'Haitiano', 'description' => 'ht'],
                    (object) ['name' => 'Húngaro', 'description' => 'hu'],
                    (object) ['name' => 'Armenio', 'description' => 'hy'],
                    (object) ['name' => 'Herero', 'description' => 'hz'],
                    (object) ['name' => 'Interlingua', 'description' => 'ia'],
                    (object) ['name' => 'Indonesio', 'description' => 'id'],
                    (object) ['name' => 'Interlingue', 'description' => 'ie'],
                    (object) ['name' => 'Igbo', 'description' => 'ig'],
                    (object) ['name' => 'Yi de Sichuan', 'description' => 'ii'],
                    (object) ['name' => 'Inupiaq', 'description' => 'ik'],
                    (object) ['name' => 'Ido', 'description' => 'io'],
                    (object) ['name' => 'Islandés', 'description' => 'is'],
                    (object) ['name' => 'Italiano', 'description' => 'it'],
                    (object) ['name' => 'Inuktitut', 'description' => 'iu'],
                    (object) ['name' => 'Japonés', 'description' => 'ja'],
                    (object) ['name' => 'Javanés', 'description' => 'jv'],
                    (object) ['name' => 'Georgiano', 'description' => 'ka'],
                    (object) ['name' => 'Kongo', 'description' => 'kg'],
                    (object) ['name' => 'Kikuyu', 'description' => 'ki'],
                    (object) ['name' => 'Kuanyama', 'description' => 'kj'],
                    (object) ['name' => 'Kazajo', 'description' => 'kk'],
                    (object) ['name' => 'Groenlandés', 'description' => 'kl'],
                    (object) ['name' => 'Jemer', 'description' => 'km'],
                    (object) ['name' => 'Canarés', 'description' => 'kn'],
                    (object) ['name' => 'Coreano', 'description' => 'ko'],
                    (object) ['name' => 'Kanuri', 'description' => 'kr'],
                    (object) ['name' => 'Cachemiro', 'description' => 'ks'],
                    (object) ['name' => 'Kurdo', 'description' => 'ku'],
                    (object) ['name' => 'Komi', 'description' => 'kv'],
                    (object) ['name' => 'Córnico', 'description' => 'kw'],
                    (object) ['name' => 'Kirguís', 'description' => 'ky'],
                    (object) ['name' => 'Latín', 'description' => 'la'],
                    (object) ['name' => 'Luxemburgués', 'description' => 'lb'],
                    (object) ['name' => 'Ganda', 'description' => 'lg'],
                    (object) ['name' => 'Limburgués', 'description' => 'li'],
                    (object) ['name' => 'Lingala', 'description' => 'ln'],
                    (object) ['name' => 'Lao', 'description' => 'lo'],
                    (object) ['name' => 'Lituano', 'description' => 'lt'],
                    (object) ['name' => 'Luba-Katanga', 'description' => 'lu'],
                    (object) ['name' => 'Letón', 'description' => 'lv'],
                    (object) ['name' => 'Malgache', 'description' => 'mg'],
                    (object) ['name' => 'Marshalés', 'description' => 'mh'],
                    (object) ['name' => 'Maorí', 'description' => 'mi'],
                    (object) ['name' => 'Macedonio', 'description' => 'mk'],
                    (object) ['name' => 'Malayalam', 'description' => 'ml'],
                    (object) ['name' => 'Mongol', 'description' => 'mn'],
                    (object) ['name' => 'Maratí', 'description' => 'mr'],
                    (object) ['name' => 'Malayo', 'description' => 'ms'],
                    (object) ['name' => 'Maltés', 'description' => 'mt'],
                    (object) ['name' => 'Birmano', 'description' => 'my'],
                    (object) ['name' => 'Nauruano', 'description' => 'na'],
                    (object) ['name' => 'Noruego bokmål', 'description' => 'nb'],
                    (object) ['name' => 'Ndebele del norte', 'description' => 'nd'],
                    (object) ['name' => 'Nepalí', 'description' => 'ne'],
                    (object) ['name' => 'Ndonga', 'description' => 'ng'],
                    (object) ['name' => 'Neerlandés', 'description' => 'nl'],
                    (object) ['name' => 'Noruego nynorsk', 'description' => 'nn'],
                    (object) ['name' => 'Noruego', 'description' => 'no'],
                    (object) ['name' => 'Ndebele del sur', 'description' => 'nr'],
                    (object) ['name' => 'Navajo', 'description' => 'nv'],
                    (object) ['name' => 'Chichewa', 'description' => 'ny'],
                    (object) ['name' => 'Occitano', 'description' => 'oc'],
                    (object) ['name' => 'Ojibwa', 'description' => 'oj'],
                    (object) ['name' => 'Oromo', 'description' => 'om'],
                    (object) ['name' => 'Oriya', 'description' => 'or'],
                    (object) ['name' => 'Osetio', 'description' => 'os'],
                    (object) ['name' => 'Panyabí', 'description' => 'pa'],
                    (object) ['name' => 'Pali', 'description' => 'pi'],
                    (object) ['name' => 'Polaco', 'description' => 'pl'],
                    (object) ['name' => 'Pastún', 'description' => 'ps'],
                    (object) ['name' => 'Portugués', 'description' => 'pt'],
                    (object) ['name' => 'Quechua', 'description' => 'qu'],
                    (object) ['name' => 'Romanche', 'description' => 'rm'],
                    (object) ['name' => 'Kirundi', 'description' => 'rn'],
                    (object) ['name' => 'Rumano', 'description' => 'ro'],
                    (object) ['name' => 'Ruso', 'description' => 'ru'],
                    (object) ['name' => 'Kinyarwanda', 'description' => 'rw'],
                    (object) ['name' => 'Sánscrito', 'description' => 'sa'],
                    (object) ['name' => 'Sardo', 'description' => 'sc'],
                    (object) ['name' => 'Sindhi', 'description' => 'sd'],
                    (object) ['name' => 'Sami septentrional', 'description' => 'se'],
                    (object) ['name' => 'Sango', 'description' => 'sg'],
                    (object) ['name' => 'Cingalés', 'description' => 'si'],
                    (object) ['name' => 'Eslovaco', 'description' => 'sk'],
                    (object) ['name' => 'Esloveno', 'description' => 'sl'],
                    (object) ['name' => 'Samoano', 'description' => 'sm'],
                    (object) ['name' => 'Shona', 'description' => 'sn'],
                    (object) ['name' => 'Somalí', 'description' => 'so'],
                    (object) ['name' => 'Albanés', 'description' => 'sq'],
                    (object) ['name' => 'Serbio', 'description' => 'sr'],
                    (object) ['name' => 'Suazi', 'description' => 'ss'],
                    (object) ['name' => 'Sesotho', 'description' => 'st'],
                    (object) ['name' => 'Sundanés', 'description' => 'su'],
                    (object) ['name' => 'Sueco', 'description' => 'sv'],
                    (object) ['name' => 'Suajili', 'description' => 'sw'],
                    (object) ['name' => 'Tamil', 'description' => 'ta'],
                    (object) ['name' => 'Telugu', 'description' => 'te'],
                    (object) ['name' => 'Tayiko', 'description' => 'tg'],
                    (object) ['name' => 'Tailandés', 'description' => 'th'],
                    (object) ['name' => 'Tigriña', 'description' => 'ti'],
                    (object) ['name' => 'Turcomano', 'description' => 'tk'],
                    (object) ['name' => 'Tagalo', 'description' => 'tl'],
                    (object) ['name' => 'Tswana', 'description' => 'tn'],
                    (object) ['name' => 'Tongano', 'description' => 'to'],
                    (object) ['name' => 'Turco', 'description' => 'tr'],
                    (object) ['name' => 'Tsonga', 'description' => 'ts'],
                    (object) ['name' => 'Tártaro', 'description' => 'tt'],
                    (object) ['name' => 'Twi', 'description' => 'tw'],
                    (object) ['name' => 'Tahitiano', 'description' => 'ty'],
                    (object) ['name' => 'Uigur', 'description' => 'ug'],
                    (object) ['name' => 'Ucraniano', 'description' => 'uk'],
                    (object) ['name' => 'Urdu', 'description' => 'ur'],
                    (object) ['name' => 'Uzbeko', 'description' => 'uz'],
                    (object) ['name' => 'Venda', 'description' => 've'],
                    (object) ['name' => 'Vietnamita', 'description' => 'vi'],
                    (object) ['name' => 'Volapük', 'description' => 'vo'],
                    (object) ['name' => 'Valón', 'description' => 'wa'],
                    (object) ['name' => 'Wolof', 'description' => 'wo'],
                    (object) ['name' => 'Xhosa', 'description' => 'xh'],
                    (object) ['name' => 'Yidis', 'description' => 'yi'],
                    (object) ['name' => 'Yoruba', 'description' => 'yo'],
                    (object) ['name' => 'Zhuang', 'description' => 'za'],
                    (object) ['name' => 'Chino', 'description' => 'zh'],
                    (object) ['name' => 'Zulú', 'description' => 'zu',]
                ],
            ],
            (object) [
                'name' => 'Plataformas de Notificación',
                'description' => 'Plataformas o proveedores utilizados para el envío de notificaciones.',
                'subitems' => [
                    (object) [
                        'name' => 'Hablame',
                        'description' => 'Proveedor colombiano para el envío de mensajes SMS, WhatsApp y voz.'
                    ],
                    (object) [
                        'name' => 'Masiv',
                        'description' => 'Plataforma de comunicación omnicanal especializada en campañas masivas SMS y WhatsApp.'
                    ]
                ]
            ],
            (object) [
                'name' => 'Canales de Notificación',
                'description' => 'Canales disponibles para enviar notificaciones a los usuarios.',
                'subitems' => [
                    (object) [
                        'name' => 'SMS',
                        'description' => 'Notificación enviada como mensaje de texto a un número de teléfono móvil.',
                        'settings' => [
                            'platforms' => [
                                (object) [
                                    'notification_platform_id' => 334,
                                    'variables' => [
                                        'url' => [
                                            'label' => 'URL',
                                            'value' => 'https://api103.hablame.co/api/sms/v3/send/priority'
                                        ],
                                        'account' => [
                                            'label' => 'Cuenta',
                                            'value' => 'CAMBIAR_CUENTA'
                                        ],
                                        'api_key' => [
                                            'label' => 'Llave API',
                                            'value' => 'CAMBIAR_API_KEY'
                                        ],
                                        'token' => [
                                            'label' => 'Token',
                                            'value' => 'CAMBIAR_TOKEN'
                                        ]
                                    ]
                                ],
                                (object) [
                                    'notification_platform_id' => 335,
                                    'variables' => [
                                        'url' => [
                                            'label' => 'URL',
                                            'value' => 'https://api-sms.masivapp.com/send-message'
                                        ],
                                        'user' => [
                                            'label' => 'Usuario',
                                            'value' => 'CAMBIAR_USUARIO'
                                        ],
                                        'password' => [
                                            'label' => 'Contraseña',
                                            'value' => 'CAMBIAR_CLAVE'
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ],
                    (object) [
                        'name' => 'Correo Electrónico',
                        'description' => 'Notificación enviada al correo electrónico del usuario.',
                        'settings' => [
                            'platforms' => [
                                (object) [
                                    'notification_platform_id' => 334,
                                    'variables' => [
                                        'url' => [
                                            'label' => 'URL',
                                            'value' => 'https://www.hablame.co/api/email/v5/send'
                                        ],
                                        'email' => [
                                            'label' => 'Correo',
                                            'value' => 'correo@example.com'
                                        ],
                                        'token' => [
                                            'label' => 'Token',
                                            'value' => 'CAMBIAR_TOKEN'
                                        ]
                                    ]
                                ],
                                (object) [
                                    'notification_platform_id' => 335,
                                    'variables' => [
                                        'url' => [
                                            'label' => 'URL',
                                            'value' => 'https://www.hablame.co/api/email/v5/send'
                                        ],
                                        'email' => [
                                            'label' => 'Correo',
                                            'value' => 'correo@example.com'
                                        ],
                                        'user' => [
                                            'label' => 'Usuario',
                                            'value' => 'CAMBIAR_USUARIO'
                                        ],
                                        'password' => [
                                            'label' => 'Contraseña',
                                            'value' => 'CAMBIAR_CLAVE'
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ],
                    (object) ['name' => 'WhatsApp', 'description' => 'Notificación enviada a través de la plataforma de mensajería WhatsApp.'],
                    (object) ['name' => 'Llamada Telefónica', 'description' => 'Notificación mediante una llamada de voz al número registrado.'],
                    (object) ['name' => 'Mensaje Interno', 'description' => 'Notificación mostrada dentro del sistema o plataforma.']
                ]
            ],
            (object) [
                'name' => 'ARL',
                'description' => 'Aseguradoras de Riesgos Laborales en Colombia.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipo de entidad de salud',
                'description' => 'Tipos de entidades Prestadoras de Salud.',
                'subitems' => [
                    (object) ['name' => 'EPS', 'description' => 'Entidad Promotora de Salud responsable de la afiliación y el acceso a los servicios de salud.'],
                    (object) ['name' => 'IPS', 'description' => 'Institución Prestadora de Servicios de Salud encargada de la atención médica directa al paciente.']
                ]
            ],
            (object) [
                'name' => 'Entidades de salud',
                'description' => 'Entidades Promotoras de Salud en Colombia.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Caja de Compensación',
                'description' => 'Cajas de Compensación Familiar en Colombia.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Fondo de Pensiones',
                'description' => 'Fondos de pensiones en Colombia.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Habilidades',
                'description' => 'Conjunto de competencias técnicas y blandas orientadas a manejar herramientas y procesos, comunicarse y colaborar eficazmente, planificar y liderar proyectos, e innovar para resolver problemas.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipo de empresa',
                'description' => 'Divisiones o departamentos dentro de una organización, cada uno especializado en funciones y responsabilidades específicas que contribuyen al cumplimiento de los objetivos de la empresa.',
                'subitems' => [
                    (object) [
                        'name' => 'Sociedad por Acciones Simplificada',
                        'description' => 'Estructura flexible, responsabilidad limitada al aporte de capital y facilidad de constitución.',
                        'settings' => (object) [
                            'abreviature' => 'S.A.S.'
                        ]
                    ],
                    (object) [
                        'name' => 'Sociedad de Responsabilidad Limitada',
                        'description' => 'Responsabilidad limitada de los socios y participación no representada por acciones.',
                        'settings' => (object) [
                            'abreviature' => 'Ltda.'
                        ]
                    ],
                    (object) [
                        'name' => 'Sociedad Anónima',
                        'description' => 'Capital dividido en acciones libremente negociables, ideal para grandes inversiones.',
                        'settings' => (object) [
                            'abreviature' => 'S.A.'
                        ]
                    ],
                    (object) [
                        'name' => 'Sociedad en Comandita por Acciones',
                        'description' => 'Combina socios gestores con responsabilidad ilimitada y socios comanditarios con responsabilidad limitada.',
                        'settings' => (object) [
                            'abreviature' => 'S.C.A.'
                        ]
                    ],
                    (object) [
                        'name' => 'Sociedad Colectiva',
                        'description' => 'Todos los socios responden de forma solidaria e ilimitada por las obligaciones sociales.',
                        'settings' => (object) [
                            'abreviature' => 'S.C.'
                        ]
                    ],
                    (object) [
                        'name' => 'Empresa Unipersonal',
                        'description' => 'Constituida por una sola persona con patrimonio independiente del personal.',
                        'settings' => (object) [
                            'abreviature' => 'E.U.'
                        ]
                    ],
                    (object) [
                        'name' => 'Cooperativa',
                        'description' => 'Organización de economía solidaria, propiedad de sus asociados, para el beneficio común.',
                        'settings' => (object) [
                            'abreviature' => 'Coop.'
                        ]
                    ],
                    (object) [
                        'name' => 'Fundación',
                        'description' => 'Entidad sin ánimo de lucro creada para fines sociales, culturales o comunitarios.',
                        'settings' => (object) [
                            'abreviature' => 'Fund.'
                        ]
                    ]
                ]
            ],
            (object) [
                'name' => 'Documentos fiscales',
                'description' => 'Son aquellos registros oficiales que acreditan y respaldan las obligaciones tributarias de una persona natural o jurídica frente a la administración tributaria.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Programas de vigilancia epidemiológica',
                'description' => 'Es un conjunto de actividades de prevención, control y seguimiento orientadas a proteger la salud de los trabajadores, identificando y evaluando los riesgos ocupacionales a los que están expuestos.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Motivos solicitud de personal',
                'description' => 'Son las razones o circunstancias que originan la necesidad de vincular nuevo talento a la organización, como renuncias, terminación de contrato, creación de nuevos cargos o incremento de la demanda de trabajo.',
                'subitems' => [
                    (object) ['name' => 'Renuncia', 'description' => 'Se presenta cuando un trabajador decide voluntariamente finalizar su vínculo laboral con la organización, generando la necesidad de cubrir su cargo.'],
                    (object) ['name' => 'Terminación de contrato', 'description' => 'Ocurre cuando la relación laboral finaliza por cumplimiento de término, decisión de la empresa o causas legales, requiriendo reemplazar al colaborador saliente.'],
                    (object) ['name' => 'Personal nuevo', 'description' => 'Se da cuando la organización necesita crear o ampliar cargos para responder a nuevas demandas, proyectos o crecimiento institucional.'],
                ]
            ],
            (object) [
                'name' => 'Tipos de servicios',
                'description' => 'Son las razones, circunstancias o apoyos externos que originan la necesidad de vincular o evaluar talento en la organización. Además de situaciones internas como renuncias, terminación de contrato o creación de nuevos cargos, también pueden incluir servicios especializados que prestan entidades externas para procesos de selección, validación o seguridad laboral.',
                'subitems' => [
                    (object) ['name' => 'Polígrafo', 'description' => 'Incluye pruebas y valoraciones realizadas por entidades especializadas externas, como el examen de polígrafo u otros servicios de validación, que apoyan los procesos de selección y garantizan la confiabilidad de la vinculación del talento.'],
                ]
            ],
            (object) [
                'name' => 'Proveedores de servicios',
                'description' => 'Corresponden a entidades externas que brindan apoyo especializado en los procesos de gestión del talento humano. Estos servicios complementan la labor interna de la organización mediante evaluaciones, validaciones o pruebas que fortalecen la confiabilidad, la seguridad y la idoneidad en la vinculación de nuevos colaboradores.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Periodos de pago',
                'description' => 'Los periodos de pago definen la frecuencia o condición bajo la cual se liquida y entrega la remuneración al colaborador. Estos ciclos permiten organizar la gestión de nómina, asegurar el cumplimiento normativo y adaptarse a modalidades contractuales o acuerdos específicos según la naturaleza del trabajo.',
                'subitems' => [
                    (object) ['name' => 'Mensual', 'description' => 'Pago que se realiza una vez al mes, generalmente al cierre del periodo mensual o en una fecha establecida por la organización.'],
                    (object) ['name' => 'Semanal', 'description' => 'Pago efectuado cada semana, aplicable a modalidades operativas o contratos por jornal donde los ciclos laborales son cortos.'],
                    (object) ['name' => 'Quincenal', 'description' => 'Pago dividido en dos cortes dentro del mes, típicamente en los días 15 y 30, o según el calendario interno de la empresa.'],
                    (object) ['name' => 'Por proyecto', 'description' => 'Pago que se liquida al finalizar un proyecto, hito o entregable específico, según los términos establecidos en el contrato.'],
                    (object) ['name' => 'Cumplimiento de actividades', 'description' => 'Pago condicionado a la ejecución o finalización de tareas, actividades o metas definidas dentro del contrato o plan de trabajo.'],
                    (object) ['name' => 'Al finalizar contrato', 'description' => 'Pago que se realiza al término de la relación laboral o contractual, liquidando conceptos pendientes según lo estipulado legalmente.'],
                ]
            ],
            (object) [
                'name' => 'Tipos de pólizas',
                'description' => 'Clasificación de las pólizas emitidas por la compañía, agrupadas según su naturaleza, cobertura y características contractuales. Estos tipos permiten estandarizar la gestión, facilitar la administración de riesgos y garantizar que cada producto se ajuste a las necesidades específicas del asegurado.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de otrosí',
                'description' => 'Modificaciones, aclaraciones o adiciones realizadas a una póliza vigente. Estos otrosí permiten ajustar condiciones, actualizar información o complementar coberturas sin necesidad de emitir una nueva póliza, asegurando la continuidad contractual y la trazabilidad del cambio.',
                'settings' => (object) [
                    'fields' => [
                        'headquarter_id' => [
                            'label' => 'Sede',
                            'rules' => ['required', 'nullable']
                        ],
                        'contract_type_id' => [
                            'label' => 'Tipo de contrato',
                            'rules' => ['required', 'nullable']
                        ],
                        'position_id' => [
                            'label' => 'Cargo',
                            'rules' => ['required', 'nullable']
                        ],
                        'salary' => [
                            'label' => 'Salario',
                            'rules' => ['required', 'nullable']
                        ],
                        'payment_period_id' => [
                            'label' => 'Periodo de pago',
                            'rules' => ['required', 'nullable']
                        ],
                        'start_date' => [
                            'label' => 'Fecha inicio de labores',
                            'rules' => ['required', 'nullable']
                        ],
                        'end_date' => [
                            'label' => 'Fecha fin de labores',
                            'rules' => ['required', 'nullable']
                        ],
                    ]
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de Notificación',
                'description' => 'Tipos de notificaciones para enviar a los usuarios.',
                'subitems' => [
                    (object) [
                        'name' => 'Notificación de registro de usuario',
                        'description' => 'Notificación enviada al usuario al completar exitosamente su registro en el sistema, con información de bienvenida y confirmación de la creación de su cuenta.',
                        'settings' => (object) [
                            'variables' => [
                                [
                                    'label' => 'Usuario',
                                    'path'  => 'user',
                                    'groups' => [
                                        [
                                            'title' => 'Datos personales',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':first_name'],
                                                ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':second_name'],
                                                ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':first_last_name'],
                                                ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':second_last_name']
                                            ]
                                        ],
                                        [
                                            'title' => 'Datos de identificación',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':document_type'],
                                                ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':document_issue_country'],
                                                ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':document_issue_departament'],
                                                ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':document_issue_city'],
                                                ['label' => 'Documento', 'field' => 'document_number', 'key' => ':document_number'],
                                                ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':document_issue_date']
                                            ]
                                        ],
                                        [
                                            'title' => 'Lugar y fecha de nacimiento',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':birth_country'],
                                                ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':birth_departament'],
                                                ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':birth_city'],
                                                ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':birth_date']
                                            ]
                                        ],
                                        [
                                            'title' => 'Datos de contacto',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':dialing_country'],
                                                ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':phone_number'],
                                                ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':email']
                                            ]
                                        ],
                                        [
                                            'title' => 'Residencia actual',
                                            'path'  => 'person',
                                            'fields' => [
                                                ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':country'],
                                                ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':departament'],
                                                ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':city'],
                                                ['label' => 'Dirección', 'field' => 'address', 'key' => ':address'],
                                                ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':neighborhood']
                                            ]
                                        ]
                                    ]
                                ],
                            ],
                            'channels' => [

                            ]
                        ]
                    ],
                ]
            ],
            (object) [
                'name' => 'Tipos de Permisos',
                'description' => 'Clasificación de los permisos laborales otorgados a los trabajadores para ausentarse de sus funciones durante un período determinado, ya sea por motivos personales, familiares, médicos o legales. Estos permisos permiten gestionar de forma controlada y trazable las ausencias, garantizando el cumplimiento de la normativa laboral y las políticas internas de la organización.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Empleado',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Permiso',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de permiso', 'field' => 'absence_type.name', 'key' => ':absence_type_name'],
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                                        ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                                        ['label' => 'Compensación', 'field' => 'compensation', 'key' => ':compensation']
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de Vacaciones',
                'description' => 'Clasificación de las modalidades de vacaciones otorgadas a los trabajadores, que definen las condiciones, duración y forma de disfrute del descanso remunerado conforme a la normativa laboral vigente y las políticas internas de la organización. Estas categorías permiten una gestión clara, controlada y trazable de los períodos de descanso, garantizando el cumplimiento legal y la correcta planificación del talento humano.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Empleado',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Vacaciones',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de vacaciones', 'field' => 'vacation_type.name', 'key' => ':vacation_type_name'],
                                        ['label' => 'Periodo', 'field' => 'period', 'key' => ':period'],
                                        ['label' => 'Dias', 'field' => 'days', 'key' => ':days'],
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de Cesantías',
                'description' => 'Clasificación de las modalidades de cesantías aplicables a los trabajadores, que definen la forma de liquidación, consignación y manejo de este beneficio social conforme a la normativa laboral vigente y las políticas internas de la organización. Estas categorías permiten una gestión clara, controlada y trazable de las cesantías, garantizando el cumplimiento legal y una adecuada administración de los recursos del trabajador.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Retiro de cesantías',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de retiro de cesantías', 'field' => 'severance_type.name', 'key' => ':severance_type_name'],
                                        ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de Incapacidades',
                'description' => 'Clasificación de las modalidades de incapacidades médicas otorgadas a los trabajadores, que determinan el origen, duración y condiciones de reconocimiento de la incapacidad conforme a la normativa laboral y de seguridad social vigente. Estas categorías permiten una gestión clara, controlada y trazable de los periodos de incapacidad, garantizando el cumplimiento legal y la correcta administración de los derechos y prestaciones del trabajador.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Incapacidad',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de incapacidad', 'field' => 'disability_type.name', 'key' => ':disability_type_name'],
                                        ['label' => 'CIE codigo', 'field' => 'cie_code.settings.code', 'key' => ':cie_code_code'],
                                        ['label' => 'CIE', 'field' => 'cie_code.name', 'key' => ':cie_code_name'],
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                                        ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de Licencias',
                'description' => 'Clasificación de las licencias laborales otorgadas a los trabajadores para ausentarse temporalmente de sus funciones por motivos personales, familiares, legales o institucionales, conforme a la normativa laboral vigente y las políticas internas de la organización. Estas categorías permiten una gestión ordenada, controlada y trazable de las licencias, garantizando el cumplimiento legal y la adecuada administración del tiempo laboral.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Licencia',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de licencia', 'field' => 'license_type.name', 'key' => ':license_type_name'],
                                        ['label' => 'Fecha inicio', 'field' => 'start_date', 'key' => ':start_date'],
                                        ['label' => 'Fecha fin', 'field' => 'end_date', 'key' => ':end_date'],
                                        ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de Certificados',
                'description' => 'Corresponde a la clasificación de los distintos certificados laborales y administrativos que la organización puede emitir para sus trabajadores, según su vínculo contractual y la finalidad del documento.',
                'subitems' => [
                    (object) [
                        'name' => 'Certificado laboral',
                        'description' => 'Documento que acredita la relación laboral entre el trabajador y la empresa, indicando información como cargo, tipo de contrato y periodo de vinculación.'
                    ],
                    (object) [
                        'name' => 'Certificado de ingresos y retenciones',
                        'description' => 'Documento que certifica los ingresos percibidos por el trabajador y las retenciones efectuadas durante un periodo fiscal determinado, utilizado para trámites tributarios, financieros y legales.'
                    ],
                ]
            ],
            (object) [
                'name' => 'Tipos de Renuncias',
                'description' => 'Clasificación de las diferentes modalidades de renuncia voluntaria del trabajador, según las condiciones y circunstancias en que se presenta la finalización del vínculo laboral.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Renuncia',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de renuncia', 'field' => 'resignation_type.name', 'key' => ':resignation_type_name'],
                                        ['label' => 'Fecha', 'field' => 'date', 'key' => ':date'],
                                        ['label' => 'Motivo', 'field' => 'reason', 'key' => ':reason'],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Tipos de Terminación de contrato',
                'description' => 'Clasificación de las modalidades mediante las cuales el empleador da por terminado el contrato de trabajo, de acuerdo con la normativa laboral vigente.',
                'settings' => (object) [
                    'variables' => [
                        [
                            'label' => 'Persona',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Datos personales',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Primer nombre', 'field' => 'first_name', 'key' => ':person_first_name'],
                                        ['label' => 'Segundo nombre', 'field' => 'second_name', 'key' => ':person_second_name'],
                                        ['label' => 'Primer apellido', 'field' => 'first_last_name', 'key' => ':person_first_last_name'],
                                        ['label' => 'Segundo apellido', 'field' => 'second_last_name', 'key' => ':person_second_last_name']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de identificación',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Tipo documento', 'field' => 'document_type.settings.abreviature', 'key' => ':person_document_type'],
                                        ['label' => 'País', 'field' => 'document_issue_city.departament.country.name', 'key' => ':person_document_issue_country'],
                                        ['label' => 'Departamento', 'field' => 'document_issue_city.departament.name', 'key' => ':person_document_issue_departament'],
                                        ['label' => 'Ciudad', 'field' => 'document_issue_city.name', 'key' => ':person_document_issue_city'],
                                        ['label' => 'Documento', 'field' => 'document_number', 'key' => ':person_document_number'],
                                        ['label' => 'Fecha de expedición', 'field' => 'document_issue_date', 'key' => ':person_document_issue_date']
                                    ]
                                ],
                                [
                                    'title' => 'Lugar y fecha de nacimiento',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'birth_city.departament.country.name', 'key' => ':person_birth_country'],
                                        ['label' => 'Departamento', 'field' => 'birth_city.departament.name', 'key' => ':person_birth_departament'],
                                        ['label' => 'Ciudad', 'field' => 'birth_city.name', 'key' => ':person_birth_city'],
                                        ['label' => 'Fecha de nacimiento', 'field' => 'birth_date', 'key' => ':person_birth_date']
                                    ]
                                ],
                                [
                                    'title' => 'Datos de contacto',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'Código', 'field' => 'dialing_country.phone_code', 'key' => ':person_dialing_country'],
                                        ['label' => 'Teléfono', 'field' => 'phone_number', 'key' => ':person_phone_number'],
                                        ['label' => 'Correo electrónico', 'field' => 'email', 'key' => ':person_email']
                                    ]
                                ],
                                [
                                    'title' => 'Residencia actual',
                                    'path'  => 'person',
                                    'fields' => [
                                        ['label' => 'País', 'field' => 'city.departament.country.name', 'key' => ':person_country'],
                                        ['label' => 'Departamento', 'field' => 'city.departament.name', 'key' => ':person_departament'],
                                        ['label' => 'Ciudad', 'field' => 'city.name', 'key' => ':person_city'],
                                        ['label' => 'Dirección', 'field' => 'address', 'key' => ':person_address'],
                                        ['label' => 'Barrio', 'field' => 'neighborhood', 'key' => ':person_neighborhood']
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => 'Terminación',
                            'path'  => '',
                            'groups' => [
                                [
                                    'title' => 'Información',
                                    'path'  => '',
                                    'fields' => [
                                        ['label' => 'Tipo de terminación', 'field' => 'model.name', 'class' => TerminationType::class, 'key' => ':termination_type_name'],
                                        ['label' => 'Motivo', 'field' => 'model.name', 'class' => Reason::class, 'key' => ':reason_name'],
                                        ['label' => 'Fecha', 'field' => 'date', 'key' => ':date'],
                                        ['label' => 'Justificación', 'field' => 'reason', 'key' => ':reason'],
                                        ['label' => 'Decisión', 'field' => 'decision', 'key' => ':decision'],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Razones de Terminación de contrato',
                'description' => 'Conjunto de motivos específicos que justifican la terminación del contrato de trabajo por parte del empleador, asociados a cada tipo de terminación.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Capítulos CIE',
                'description' => 'Corresponde a la clasificación principal de la Clasificación Internacional de Enfermedades (CIE), que agrupa las enfermedades según sistemas, órganos o condiciones generales.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Grupos CIE',
                'description' => 'Subclasificación de los capítulos CIE que agrupa enfermedades relacionadas por características clínicas o etiológicas comunes.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Subgrupos CIE',
                'description' => 'Nivel de clasificación que organiza de manera más específica los grupos CIE, permitiendo una identificación más detallada de las enfermedades.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Códigos CIE',
                'description' => 'Código alfanumérico oficial de la Clasificación Internacional de Enfermedades que identifica de forma única una enfermedad, condición o diagnóstico.',
                'subitems' => [

                ]
            ],
            (object) [
                'name' => 'Parentescos',
                'description' => 'Define los tipos de relación familiar o legal que existen entre una persona y sus contactos, como padres, hijos, cónyuge u otros vínculos, utilizados para identificar relaciones personales y contactos de emergencia.',
                'subitems' => [
                    (object) [
                        'name' => 'Padre',
                        'description' => 'Relación de parentesco que identifica al progenitor masculino de una persona.'
                    ],
                    (object) [
                        'name' => 'Madre',
                        'description' => 'Relación de parentesco que identifica a la progenitora femenina de una persona.'
                    ],
                    (object) [
                        'name' => 'Hijo',
                        'description' => 'Relación de parentesco que identifica al descendiente directo de una persona.'
                    ],
                    (object) [
                        'name' => 'Hija',
                        'description' => 'Relación de parentesco que identifica a la descendiente directa de una persona.'
                    ],
                    (object) [
                        'name' => 'Cónyuge',
                        'description' => 'Relación legal o civil que une a dos personas mediante matrimonio.'
                    ],
                    (object) [
                        'name' => 'Pareja',
                        'description' => 'Relación afectiva estable entre dos personas, reconocida o no legalmente.'
                    ],
                    (object) [
                        'name' => 'Hermano',
                        'description' => 'Relación de parentesco entre personas que comparten uno o ambos progenitores.'
                    ],
                    (object) [
                        'name' => 'Hermana',
                        'description' => 'Relación de parentesco entre personas que comparten uno o ambos progenitores.'
                    ],
                    (object) [
                        'name' => 'Abuelo',
                        'description' => 'Relación de parentesco que identifica al padre del padre o de la madre.'
                    ],
                    (object) [
                        'name' => 'Abuela',
                        'description' => 'Relación de parentesco que identifica a la madre del padre o de la madre.'
                    ],
                    (object) [
                        'name' => 'Tío',
                        'description' => 'Relación de parentesco que identifica al hermano del padre o de la madre.'
                    ],
                    (object) [
                        'name' => 'Tía',
                        'description' => 'Relación de parentesco que identifica a la hermana del padre o de la madre.'
                    ],
                    (object) [
                        'name' => 'Primo',
                        'description' => 'Relación de parentesco entre personas que comparten abuelos.'
                    ],
                    (object) [
                        'name' => 'Prima',
                        'description' => 'Relación de parentesco entre personas que comparten abuelos.'
                    ],
                    (object) [
                        'name' => 'Otro',
                        'description' => 'Relación de parentesco no contemplada específicamente dentro del listado.'
                    ],
                ]
            ],
            (object) [
                'name' => 'Tallas',
                'description' => 'Catálogo de tallas para prendas y dotación.',
                'subitems' => [
                    (object) ['name' => '04', 'description' => null],
                    (object) ['name' => '06', 'description' => null],
                    (object) ['name' => '08', 'description' => null],
                    (object) ['name' => '10', 'description' => null],
                    (object) ['name' => '12', 'description' => null],
                    (object) ['name' => '14', 'description' => null],
                    (object) ['name' => '16', 'description' => null],
                    (object) ['name' => '18', 'description' => null],
                    (object) ['name' => '20', 'description' => null],
                    (object) ['name' => '22', 'description' => null],
                    (object) ['name' => '28', 'description' => null],
                    (object) ['name' => '30', 'description' => null],
                    (object) ['name' => '32', 'description' => null],
                    (object) ['name' => '34', 'description' => null],
                    (object) ['name' => '36', 'description' => null],
                    (object) ['name' => '38', 'description' => null],
                    (object) ['name' => '40', 'description' => null],
                    (object) ['name' => '42', 'description' => null],
                    (object) ['name' => 'XXXS', 'description' => null],
                    (object) ['name' => 'XXS', 'description' => null],
                    (object) ['name' => 'XS', 'description' => null],
                    (object) ['name' => 'S', 'description' => null],
                    (object) ['name' => 'M', 'description' => null],
                    (object) ['name' => 'L', 'description' => null],
                    (object) ['name' => 'XL', 'description' => null],
                    (object) ['name' => 'XXL', 'description' => null],
                    (object) ['name' => 'XXXL', 'description' => null],
                ]
            ],
        ];

        foreach ($datos as $dato) {
            $item = Item::create([
                'name' => $dato->name,
                'description' => $dato->description
            ]);

            foreach ($dato->subitems as $subdato) {
                Subitem::create([
                    'item_id' => $item->id,
                    'name' => $subdato->name,
                    'description' => $subdato->description,
                    'settings' => $subdato->settings ?? (object) []
                ]);
            }
        }*/
    }
}
