--TEST--
Memcached fetch cas & set cas
--SKIPIF--
<?php if (!extension_loaded("memcached")) print "skip"; ?>
--FILE--
<?php
$m = new Memcached();
$m->addServer('127.0.0.1', 11211, 1);

$m->delete('cas_test');
$cas_token = null;

$m->set('cas_test', 10);
$v = $m->get('cas_test', null, $cas_token);

if (is_null($cas_token)) {
	echo "Null cas token for key: cas_test value: 10\n";
	return;
}

$v = $m->cas($cas_token, 'cas_test', 11);
if (!$v) {
	echo "Error setting key: cas_test value: 11 with CAS: $cas_token\n";
	return;
}

$v = $m->get('cas_test');

if ($v !== 11) {
	echo "Wanted cas_test to be 11, value is: ";
	var_dump($v);
}
echo "OK\n";
?>
--EXPECT--
OK