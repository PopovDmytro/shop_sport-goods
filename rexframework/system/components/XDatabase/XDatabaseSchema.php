<?php
  require_once 'PEAR.php';
 class XDatabaseSchema {
  function XDatabaseSchema()
 {
 require_once 'MDB2/Schema.php';
}
 function _XDatabaseSchema() 
 {
 
 }
  function createDatabase($aInput) 
 {
 $res = null;

 if (!XDatabase::isError()) {
 
 set_time_limit(0); 
 $manager =& MDB2_Schema::factory(XDatabase::getObject());

 if (is_string($aInput)) {
 $definition = $manager->parseDatabaseDefinition($aInput);
$res = $manager->createDatabase($definition);
} elseif (is_array($aInput)) {
 $res = $manager->createDatabase($aInput);
} else {
 return null;
}
XDatabase::_checkDBResult($res);
}
return $res;
} 
 
  function updateDatabase($aInputFile, $aBackupFile = null) 
 {
 
 $res = null;

 if (!XDatabase::isError()) {
 
 set_time_limit(0); 
 $manager =& MDB2_Schema::factory(XDatabase::getObject());    
 $res = $manager->updateDatabase($aInputFile, $aBackupFile);
XDatabase::_checkDBResult($res);
}
return $res;
}

  function dumpDatabaseToFile($aOutputFile, $aDumpWhat = MDB2_SCHEMA_DUMP_ALL) 
 {
 $res = null;

 if (!XDatabase::isError()) {
 
 set_time_limit(0);

 $dump_config = array(
 'output_mode' => 'file',
 'output' => $aOutputFile
 );

 
 $schema =& MDB2_Schema::factory(XDatabase::getObject());
$definition = $schema->getDefinitionFromDatabase();

 $res = $schema->dumpDatabase($definition, $dump_config, $aDumpWhat);

 XDatabase::_checkDBResult($res);
}
return $res;
}

  function parseDatabaseDefinition($aInputFile) 
 { 
 $manager =& MDB2_Schema::factory(XDatabase::getObject());
$definition = $manager->parseDatabaseDefinition($aInputFile);
return $definition;
} 
}
?>