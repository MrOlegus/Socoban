<?php

function execute_php($code): string {
    while ((bool)strpos($code, '<?php'))
    {
        $start = strpos($code, '<?php');
        $finish = strpos($code, '?>');
        $code_with_php = substr($code, $start, $finish - $start + 2);
        $code_to_execute = substr($code_with_php, 5, $finish - $start - 5);
        echo "Start " . $code_to_execute . "Finish";
        eval($code_to_execute);
        $executed_code = $code_to_execute;
        $code = str_replace($code_with_php, $executed_code, $code);
        echo "Start " . $executed_code . "Finish";
    }
    return $code;
}