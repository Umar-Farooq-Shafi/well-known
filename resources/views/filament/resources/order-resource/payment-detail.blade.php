@php
    function formatJsonToHtml($data, $indent = 0): string
    {
        $indentation = str_repeat('   ', $indent);
        $html = '{<br>';

        foreach ($data as $key => $value) {
            $html .= $indentation . '   "' . $key . '": ';

            if (is_array($value) || is_object($value)) {
                $html .= formatJsonToHtml($value, $indent + 1);
            } else {
                $formattedValue = is_string($value) ? $value : json_encode($value);
                $html .= '"' . htmlspecialchars($formattedValue) . '"';
            }

            $html .= '<br>';
        }

        $html .= $indentation . '}';

        return $html;
    }

    $details = $payment?->details;
    $content = formatJsonToHtml($details ?? []);
@endphp

<style>
    pre {
        white-space: pre-wrap;
    }
</style>

<div class="bg-white shadow-md rounded-lg p-6 w-full max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Payment Details</h1>
    <pre class="bg-gray-100 p-4 rounded-lg w-full text-sm text-gray-800">{!! $content !!}</pre>
</div>
