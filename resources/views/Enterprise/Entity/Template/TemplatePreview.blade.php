@php
    $top = $settings['margins']['top']['length'] . $settings['margins']['top']['type'];
    $bottom = $settings['margins']['bottom']['length'] . $settings['margins']['bottom']['type'];
    $left = $settings['margins']['left']['length'] . $settings['margins']['left']['type'];
    $right = $settings['margins']['right']['length'] . $settings['margins']['right']['type'];

    $sections = $settings['sections'];

    // Resolución segura del membrete para DOMPDF
    $membretePath = $template['path'];

    if (!function_exists('toSpanishOrdinal')) {
        function toSpanishOrdinal($n)
        {
            $unidades = [
                '',
                'PRIMERA',
                'SEGUNDA',
                'TERCERA',
                'CUARTA',
                'QUINTA',
                'SEXTA',
                'SÉPTIMA',
                'OCTAVA',
                'NOVENA',
            ];
            $decenas = [
                '',
                'DÉCIMA',
                'VIGÉSIMA',
                'TRIGÉSIMA',
                'CUADRAGÉSIMA',
                'QUINCUAGÉSIMA',
                'SEXAGÉSIMA',
                'SEPTUAGÉSIMA',
                'OCTOGÉSIMA',
                'NONAGÉSIMA',
            ];

            if ($n < 10) {
                return $unidades[$n];
            }

            if ($n < 100) {
                $d = floor($n / 10);
                $u = $n % 10;

                return $u === 0 ? $decenas[$d] : $decenas[$d] . ' ' . $unidades[$u];
            }

            return (string) $n;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <style>
        html {
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center center;
            background-size: 100%;
            margin: 0px;
        }

        body {
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            font-size: 12px;
            font-family: calibri, sans-serif;
        }

        .page {
            background-repeat: no-repeat;
            background-size: cover;
        }

        .page-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .content {
            position: relative;
            z-index: 10;
            size: {{ $settings['size'] }} {{ $settings['orientation'] }};
        }

        .content-html {
            width: 100%;
        }

        .content-html p {
            margin: 0 0 8px 0;
        }

        /* TABLAS */
        /* TABLAS */
        .content-html table {
            width: 96% !important;
            max-width: 96% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            table-layout: fixed !important;
            margin: 4px auto 14px auto !important;
        }

        .content-html table td,
        .content-html table th {
            border: 1px solid #333 !important;
            padding: 5px 8px !important;
            vertical-align: middle !important;
            text-align: center !important;
            font-size: 9.5px !important;
            line-height: 1.22 !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .content-html table td {
            width: 50% !important;
        }

        .content-html table p {
            margin: 0 !important;
            padding: 0 !important;
            text-indent: 0 !important;
            line-height: 1.22 !important;
        }

        .content-html table td p:first-child {
            font-weight: 700 !important;
            text-transform: uppercase;
            margin-bottom: 2px !important;
        }

        .content-html table td p:not(:first-child),
        .content-html table td p:not(:first-child) * {
            font-weight: 400 !important;
        }

        .content-html table span,
        .content-html table div,
        .content-html table b,
        .content-html table strong {
            line-height: 1.22 !important;
            white-space: normal !important;
        }

        .content-html table td * {
            word-break: normal !important;
            overflow-wrap: break-word !important;
        }

        .content-html table th {
            font-weight: bold;
        }

        .content-html table p,
        .content-html table span,
        .content-html table div {
            margin: 0;
            padding: 0;
            white-space: normal !important;
        }

        .content-html img {
            max-width: 100% !important;
            height: auto !important;
        }

        .content-html .img {
            max-width: 250px !important;
            height: auto !important;
        }

        .content-html ul,
        .content-html ol {
            margin: 0 0 10px 18px;
            padding: 0;
        }

        .contract-list {
            margin: 0 0 10px 18px;
            padding: 0;
        }

        .contract-list li {
            margin-bottom: 8px;
            text-align: justify;
        }

        .contract-list li strong {
            display: inline;
        }

        /* .contract-list li p,
        .contract-list li div,
        .contract-list li span {
            display: inline;
            margin: 0;
            padding: 0;
        } */

        .contract-list li br {
            line-height: normal;
        }

        .contract-item {
            text-align: justify;
        }

        .contract-item-title {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            font-weight: bold;
        }

        /* .contract-item p {
            display: inline;
            margin: 0;
        } */

        .contract-list {
            margin: 0 0 10px 18px;
            padding: 0;
        }

        .contract-list>li {
            margin-bottom: 8px;
            text-align: justify;
        }

        .contract-item-title {
            font-weight: bold;
        }

        .contract-item>p:first-of-type {
            display: inline;
            margin: 0;
        }

        .contract-item>ol,
        .contract-item>ul {
            display: block;
            margin: 8px 0 8px 22px;
            padding: 0;
        }

        .contract-item>ol>li,
        .contract-item>ul>li {
            display: list-item;
            margin-bottom: 6px;
        }

        .contract-list-inline {
            margin: 0 0 10px 0;
            padding: 0;
        }

        .contract-inline-item {
            margin-bottom: 8px;
            text-align: justify;
        }

        .contract-inline-prefix {
            font-weight: bold;
            display: inline;
        }

        .contract-inline-item p:first-of-type,
        .contract-inline-item div:first-of-type,
        .contract-inline-item span:first-of-type {
            display: inline;
            margin: 0;
            padding: 0;
        }

        .contract-inline-item p {
            margin: 0 0 8px 0;
        }

        .contract-inline-item ol,
        .contract-inline-item ul {
            margin: 8px 0 8px 22px;
            padding: 0;
            list-style: none;
        }

        .contract-inline-item ol li,
        .contract-inline-item ul li {
            display: block;
            margin-bottom: 6px;
        }
    </style>
</head>

<body
    style="padding: {{ $top }} {{ $right }} {{ $bottom }} {{ $left }} !important; background-image: url('{{ $template['path'] }}');">
    <div class="page">
        <div class="content">

            @foreach ($sections as $section)
                @if ($section['settings']['is_visible'])
                    <div class="section-title">
                        {{ $section['title'] }}
                    </div>
                @endif

                @foreach ($section['subsections'] as $sub)
                    @if ($sub['settings']['is_visible'])
                        <div class="subsection-title">
                            {{ $sub['title'] }}
                        </div>
                    @endif

                    <div class="content-html">

                        {{-- TEXTO --}}
                        @if ($sub['entry_type'] === 'text')
                            @php
                                $html = $sub['content'];

                                // Decodifica entidades HTML
                                $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                                // Limpia caracteres invisibles que DOMPDF puede pintar como ?
                                $html = preg_replace('/[\x{200B}\x{200C}\x{200D}\x{FEFF}]/u', '', $html);

                                // Limpia etiquetas basura de Word
                                $html = preg_replace('/<o:p[^>]*>.*?<\/o:p>/isu', '', $html);
                                $html = preg_replace('/<\/?o:p[^>]*>/iu', '', $html);

                                // Limpia anchos y altos inline que mete Summernote
                                $html = preg_replace('/width\s*:\s*[^;"]+;?/i', '', $html);
                                $html = preg_replace('/height\s*:\s*[^;"]+;?/i', '', $html);

                                // Quita atributos width y height HTML
                                $html = preg_replace('/\swidth="[^"]*"/i', '', $html);
                                $html = preg_replace('/\sheight="[^"]*"/i', '', $html);
                            @endphp

                            {!! $html !!}
                        @endif

                        {{-- LISTA --}}
                        {{-- LISTA --}}
                        @if ($sub['entry_type'] === 'list')
                            @php
                                $listType = $sub['list_type'] ?? 'alphabetic';
                            @endphp

                            <div class="contract-list-inline">
                                @foreach ($sub['items'] as $index => $item)
                                    @php
                                        $itemContent = trim($item['content']);

                                        $itemContent = html_entity_decode(
                                            $itemContent,
                                            ENT_QUOTES | ENT_HTML5,
                                            'UTF-8',
                                        );

                                        $itemContent = preg_replace(
                                            '/[\x{200B}\x{200C}\x{200D}\x{FEFF}]/u',
                                            '',
                                            $itemContent,
                                        );

                                        $itemContent = preg_replace('/<o:p[^>]*>.*?<\/o:p>/isu', '', $itemContent);
                                        $itemContent = preg_replace('/<\/?o:p[^>]*>/iu', '', $itemContent);

                                        // Quita solo el <p> externo inicial y final si existe
                                        $itemContent = preg_replace('/^\s*<p[^>]*>/i', '', $itemContent);
                                        $itemContent = preg_replace('/<\/p>\s*$/i', '', $itemContent);

                                        $marker = match ($listType) {
                                            'alphabetic' => toSpanishOrdinal($index + 1) . '.',
                                            'numeric' => $index + 1 . '.',
                                            'ordinal' => toSpanishOrdinal($index + 1) . '.',
                                            default => $index + 1 . '.',
                                        };
                                    @endphp

                                    <div class="contract-inline-item">
                                        <span class="contract-inline-prefix">
                                            {{ $marker }} {{ $item['title'] }}:
                                        </span>
                                        {!! $itemContent !!}
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                @endforeach
            @endforeach

        </div>
    </div>
</body>

</html>
