<?php
require_once "ZabbixApi.php";
use IntelliTrend\Zabbix\ZabbixApi;

$zbx = new ZabbixApi();
$zbx -> login('http://172.16.210.111/zabbix', 'hajar', '#Hajar166');

?>
<!DOCTYPE html>
<html lang = "en">
<head>
<meta charset="utf-8">

<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  margin-top: 50px;
  margin-left:200px;
  width: 80%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #c3dcf4;
}
</style>
</head>
<body>

<table>
  <tr>
    <th>#</th>
    <th>Graph ID</th>
    <th>Name</th>
  </tr>
  


   <?php
    $params = array ( 
        'output' => array('hostid', 'host', 'name', 'status', 'maintenance_status', 'description', 'available', 'ipmi_available', 'snmp_available', 'jmx_available'),
        'selectGraphs' => "extend" 
      );
   
        $result = $zbx -> call('host.get', $params);
        $count = 0;
        $count++;

        foreach ($result as $host) {
          $_GET['hostid']= $host['hostid'];
            print '<tr>';
            echo '<td>' .$count++. '</td>';
            
            echo '<td>';
            foreach ($host['graphs'] as $graph){
             echo  $graph['graphid']. "<br>"; 
            }
            echo '</td>';
            echo '<td>'.$host['name']. '</td>' ;
        echo '<td>';
 
        } print '</tr>';
   ?>
</table> 
</body>
</html>