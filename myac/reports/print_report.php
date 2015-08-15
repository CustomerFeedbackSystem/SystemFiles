<?php
header('Content-type: application/excel');

$file=str_replace(' ','_',mysql_escape_string(trim($_POST['report_name'])));

$filename = ''.$file.'.xls';

header('Content-Disposition: attachment; filename='.$filename);
$report_body_raw = $_POST['report_body'];
$report_body=trim($report_body_raw);

$data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>

<body>
   '.$report_body.'
</body>
</html>';

echo $data;
?>