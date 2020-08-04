<?php
function checkServerVar()
{
    $vars=array('HTTP_HOST','SERVER_NAME','SERVER_PORT','SCRIPT_NAME','SCRIPT_FILENAME','PHP_SELF','HTTP_ACCEPT','HTTP_USER_AGENT');
    $missing=array();
    foreach($vars as $var)
    {
        if(!isset($_SERVER[$var]))
            $missing[]=$var;
    }
    if(!empty($missing))
        return '$_SERVER does not have '. implode(', ',$missing);
    if(realpath($_SERVER["SCRIPT_FILENAME"]) !== realpath(__FILE__))
        return '$_SERVER["SCRIPT_FILENAME"] must be the same as the entry script file path.';
    if(!isset($_SERVER["REQUEST_URI"]) && isset($_SERVER["QUERY_STRING"]))
        return 'Either $_SERVER["REQUEST_URI"] or $_SERVER["QUERY_STRING"] must exist.';
    if(!isset($_SERVER["PATH_INFO"]) && strpos($_SERVER["PHP_SELF"],$_SERVER["SCRIPT_NAME"]) !== 0)
        return 'Unable to determine URL path info. Please make sure $_SERVER["PATH_INFO"] (or $_SERVER["PHP_SELF"] and $_SERVER["SCRIPT_NAME"]) contains proper value.';
    return '';
}
function checkCaptchaSupport()
{
    if(extension_loaded('imagick'))
    {
        $imagick=new Imagick();
        $imagickFormats=$imagick->queryFormats('PNG');
    }
    if(extension_loaded('gd'))
        $gdInfo=gd_info();
    if(isset($imagickFormats) && in_array('PNG',$imagickFormats))
        return '';
    elseif(isset($gdInfo))
    {
        if($gdInfo['FreeType Support'])
            return '';
        return 'GD installed,<br />FreeType support not installed';
    }
    return 'GD or ImageMagick not installed';
}

function getServerInfo()
{
    $info[]=isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
    $info[]=@strftime('%Y-%m-%d %H:%M',time());
    return implode(' ',$info);
}

$serverInfo = getServerInfo();

$requirements = array(
    array(
        "PHP version (5.4.0 or higher)",
        version_compare(PHP_VERSION, "5.4.0",">="),
        "PHP 5.4.0 or higher is required."
    ),
    array(
        '$_SERVER variable',
        '' === $message=checkServerVar(),
        $message,
    ),
    array(
        'Reflection extension',
        class_exists('Reflection',false),
        'Reflection extension is not loaded',
    ),
    array(
        'PCRE extension',
        extension_loaded("pcre"),
        "PCRE extension is not loaded",
    ),
    array(
        'SPL extension',
        extension_loaded("SPL"),
        "SPL extension is not loaded",
    ),
    array(
        'Ctype extension',
        extension_loaded('ctype'),
        'Ctype extension is not loaded',
    ),
    array(
        'MBString extension',
        extension_loaded("mbstring"),
        'MBString extension is not loaded. Required for multibyte encoding string processing.',
    ),
    array(
        'OpenSSL extension',
        extension_loaded('openssl'),
        'OpenSSL extension is not loaded. Required by encrypt and decrypt methods.'
    ),
    array(
        'DOM extension',
        class_exists("DOMDocument",false),
        'DOM Document extension is not loaded',
    ),
    array(
        'PDO extension',
        extension_loaded('pdo'),
        'PDO extension is not loaded',
    ),
    array(
        'PDO MySQL driver',
        extension_loaded('pdo_mysql'),
        'PDO MySQL extension is not loaded',
    ),
    array(
        'Mcrypt extension',
        extension_loaded('mcrypt'),
        'Mcrypt extension is not loaded',
    ),
    array(
        'GD extension with<br />FreeType support<br />or ImageMagick<br />extension with<br />PNG support',
        '' === $message=checkCaptchaSupport(),
        $message,
    ),
    array(
        'Sockets',
        function_exists('fsockopen'),
        'fsockopen is not enabled',
    ),
    array(
        'cURL',
        function_exists('curl_version'),
        'cURL is not enabled',
    ),
    array(
        'Process',
        function_exists('proc_open'),
        'There is no way to execute a Shell command because <strong>proc_open</strong> function is not available or disabled in <strong>php.ini</strong>. You will be able run only HTTP cron tasks',
    ),
    array(
        'short_open_tag (php.ini directive)',
        (ini_get('short_open_tag') == 'On' || ini_get('short_open_tag')=='1'),
        'You need to enable <strong>short_open_tag</strong> in <strong>php.ini</strong>.',
    ),
    array(
        'Safe mode (php.ini directive) must be disabled',
        (ini_get('safe_mode') == 'Off' || ini_get('safe_mode')==''),
        'You need to disable <strong>safe_mode</strong> in <strong>php.ini</strong> to execute large amount of cron jobs at the same time.',
    ),
    array(
        'PHP mail SMTP',
        strlen(ini_get('SMTP'))>0,
        'SMTP is required for email sending',
    ),
);
$result=1;  // 1: all pass, 0: fail
foreach($requirements as $i=>$requirement)  {
    if(!$requirement[1])
        $result=0;
    if($requirement[2] === '')
        $requirements[$i][2]='&nbsp;';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="content-language" content="en"/>
    <title>Cronjobs Manager Requirements Checker</title>
    <style type="text/css">
        body
        {
            background: white;
            font-family:'Lucida Grande',Verdana,Geneva,Lucida,Helvetica,Arial,sans-serif;
            font-size:10pt;
            font-weight:normal;
        }

        #page
        {
            width: 800px;
            margin: 0 auto;
        }

        #header
        {
        }

        #content
        {
        }

        #footer
        {
            color: gray;
            font-size:8pt;
            border-top:1px solid #aaa;
            margin-top:10px;
        }

        h1
        {
            color:black;
            font-size:1.6em;
            font-weight:bold;
            margin:0.5em 0pt;
        }

        h2
        {
            color:black;
            font-size:1.25em;
            font-weight:bold;
            margin:0.3em 0pt;
        }

        h3
        {
            color:black;
            font-size:1.1em;
            font-weight:bold;
            margin:0.2em 0pt;
        }

        table.result
        {
            background:#E6ECFF none repeat scroll 0% 0%;
            border-collapse:collapse;
            width:100%;
        }

        table.result th
        {
            background:#CCD9FF none repeat scroll 0% 0%;
            text-align:left;
        }

        table.result th, table.result td
        {
            border:1px solid #BFCFFF;
            padding:0.2em;
        }

        td.passed
        {
            background-color: #60BF60;
            border: 1px solid silver;
            padding: 2px;
        }

        td.warning
        {
            background-color: #FFFFBF;
            border: 1px solid silver;
            padding: 2px;
        }

        td.failed
        {
            background-color: #FF8080;
            border: 1px solid silver;
            padding: 2px;
        }
    </style>
</head>
<body>
<div id="page">

    <div id="header">
        <h1>Online Cronjobs Manager</h1>
    </div><!-- header-->

    <div id="content">
        <h2>Description</h2>
        <p>
            This script checks if your server configuration meets the requirements
            for running <a href="http://sender.php5developer.com/">Online Cronjobs Manager</a>.
            It checks if the server is running the right version of PHP,
            if appropriate PHP extensions have been loaded, and if php.ini file settings are correct.
        </p>
        <h2>NOTE!</h2>
        <p>
            This is not a <strong>SYSTEM</strong> cron job, you usually find in cPanel (Plesk), or in linux servers where you set a time
            and a function to run and the server will automatically run the PHP script.
        </p>

        <h2>Conclusion</h2>
        <p>
            <?php if($result>0): ?>
                Congratulations! Your server configuration satisfies all requirements by Online Cronjobs Manager.
            <?php else: ?>
                Unfortunately your server configuration does not satisfy the requirements by Online Cronjobs Manager.
            <?php endif; ?>
        </p>

        <h2>Details</h2>

        <table class="result">
            <tr><th>Name</th><th>Result</th><th>Memo</th></tr>
            <?php foreach($requirements as $requirement): ?>
                <tr>
                    <td>
                        <?php echo $requirement[0]; ?>
                    </td>
                    <td class="<?php echo $requirement[1] ? 'passed' : 'failed'; ?>">
                        <?php echo $requirement[1] ? 'Passed' : 'Failed'; ?>
                    </td>
                    <td>
                        <?php if(!$requirement[1]): ?>
                        <?php echo $requirement[2]; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <table>
            <tr>
                <td class="passed">&nbsp;</td><td>passed</td>
                <td class="failed">&nbsp;</td><td>failed</td>
            </tr>
        </table>

    </div><!-- content -->

    <div id="footer">
        <?php echo $serverInfo; ?>
    </div><!-- footer -->

</div><!-- page -->
</body>
</html>