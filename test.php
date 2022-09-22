
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">   
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src = "https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> 
<style>
table {
  font-family: arial, sans-serif;
  
  border-collapse: collapse;
  margin-top : 50px;
  margin-left : 100px;
   
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

.span {
  border-radius: 5px;
  color: white;
  padding: 8px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
}

/* //green */
.span1 {
  background-color: #447749; 
  margin : 0px;
  
}
/* red */
.span2 {
  background-color: #8f2f2f ;
  margin : 0px;
}

/* gray */
.span3 {
  background-color: #b4b4b4;
  color : black;
  margin: 0px;
  border-radius : 3px;
}
span:hover {
  background-color: #919191;
}

.span1:hover {
  background-color : #4e8a54;
}
.span2:hover {
  background-color : #913e3e;
}

</style>
</head>
<body style="margin:20px auto">
<div class="container">
<div class="row header" style="text-align:center; color: green">
</div>
<table id="myTable">
  <thead>
  <tr>
    <th>#</th>
    <th onclick = "sortTable(0)">Host Name</th>
    <th onclick = "sortTable(1)">Interface</th>
    <th onclick = "sortTable(2)">Groups</th>
    <th onclick = "sortTable(3)">Availability</th>
    <th onclick = "sortTable(4)">Status</th>
    
  </tr>
</head>

<script> 
 $(document).ready(function(){
    $('#myTable').dataTable()});
 </script>
<script>

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*loop that will continue until there is no switching has been done:*/
  while (switching) {
    
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      
      shouldSwitch = false;
      /*Get the two elements you want to compare, one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place, based on the direction*/
      if (dir == "asc") { 
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") { 
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>
  <?php
    $params = array ( 
        'output' => array('hostid', 'host', 'name', 'status', 'maintenance_status', 'description', 'available', 'ipmi_available', 'snmp_available', 'jmx_available'),
        'selectInterfaces' => array('interfaceid', 'main', 'type', 'useip', 'ip', 'dns', 'port'),
        'selectGroups' => array('groupid','name','internal','flags')
        
      );

        $result = $zbx -> call('host.get', $params);
        $count = 0;
        $count++;
        foreach ($result as $host) {

          
            print  '<tr>';
            echo '<td>' .$count++. '</td>';
            echo '<td><a href = "test3.php?hostid='.$host['hostid'].'">'.$host['name'].'</a> </td>' ; //href for graph

        echo '<td>';
        foreach ($host["interfaces"] as $interfaces) {} echo $interfaces["ip"]."<br>"; echo'</td>';
       
        echo '<td>'; //in the same box
        foreach ($host['groups'] as $group) { 
          echo $group['name']."<br>"; //<br> new line
        };
        echo '</td>';
  
        echo '<td>';
        
        $availability = "";
        if ( $host['available'] == 1 && $host['ipmi_available'] == 0 && $host['snmp_available'] == 0 && $host['jmx_available'] == 0)
            echo'<span class ="span span1">ZBX</span>','<span class ="span span3">IPMI</span>','<span class ="span span3">SNMP</span>','<span class ="span span3">JMX</span>';
// span1 = green , span2 = red , span3 = gray
         else if ( $host['available'] == 0 && $host['ipmi_available'] == 1 && $host['snmp_available'] == 0 && $host['jmx_available']  == 0)
            echo '<span class ="span span3">ZBX</span>','<span class ="span span1">IPMI</span>','<span class ="span span3">SNMP</span>','<span class ="span span3">JMX</span>';

        else if ( $host['available'] == 0 && $host['ipmi_available'] == 0 && $host['snmp_available'] == 1 && $host['jmx_available'] == 0)
            echo '<span class ="span span3">ZBX</span>','<span class ="span span3">IPMI</span>','<span class ="span span1">SNMP</span>','<span class ="span span3">JMX</span>';

        else if ( $host['available'] == 0 && $host['ipmi_available'] == 0 && $host['snmp_available'] == 0 && $host['jmx_available']  == 1)
             echo '<span class ="span span3">ZBX</span>','<span class ="span span3">IPMI</span>','<span class ="span span3">SNMP</span>','<span class ="span span1">JMX</span>';

        else if ( $host['available'] == 2 && $host['ipmi_available'] == 0 && $host['snmp_available'] == 0 && $host['jmx_available'] == 0)
            echo '<span class ="span span2">ZBX</span>','<span class ="span span3">IPMI</span>','<span class ="span span3">SNMP</span>','<span class ="span span3">JMX</span>' ;

         else if ( $host['available'] == 0 && $host['ipmi_available'] == 2 && $host['snmp_available'] == 0 && $host['jmx_available']  == 0)
            echo '<span class ="span span3">ZBX</span>','<span class ="span span2">IPMI</span>','<span class ="span span3">SNMP</span>','<span class ="span span3">JMX</span>';

        else if ( $host['available'] == 0 && $host['ipmi_available'] == 0 && $host['snmp_available'] == 2 && $host['jmx_available'] == 0)
            echo '<span class ="span span3">ZBX</span>','<span class ="span span3">IPMI</span>','<span class ="span span2">SNMP</span>','<span class ="span span3">JMX</span>';

        else if ( $host['available'] == 0 && $host['ipmi_available'] == 0 && $host['snmp_available'] == 0 && $host['jmx_available']  == 2)
             echo '<span class ="span span3">ZBX</span>','<span class ="span span3">IPMI</span>','<span class ="span span3">SNMP</span>','<span class ="span span2">JMX</span>';

        else if ($host['available'] == 0 && $host['ipmi_available'] == 0 && $host['snmp_available'] == 0 && $host['jmx_available'] == 0)  
             echo '<span class ="span span3">ZBX</span>','<span class ="span span3">IPMI</span>','<span class ="span span3">SNMP</span>','<span class ="span span3">JMX</span>'; 
        echo '</td>' ;
        
        echo '<td>';
      
        $status = "";
        if ($host['status'] == 0)
          echo '<span class ="span span1">Enable</span>';
        else if ($host['status'] == 1)
          echo '<span class ="span span2">Disable</span>';
          echo '</td>' ;

} print '</tr>';
  ?>


</table> 
</div>
</body>
</html>
