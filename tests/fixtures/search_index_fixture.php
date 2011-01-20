<?php
class SearchIndexFixture extends CakeTestFixture {
	var $name = 'SearchIndex';
	var $table = 'search_index';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'data' => array('type' => 'text', 'null' => false, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'summary' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'url' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'),
		'published' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'model' => array('column' => array('model', 'foreign_key'), 'unique' => 1), 'active' => array('column' => 'active', 'unique' => 0), 'data' => array('column' => 'data', 'unique' => 0,  'type' => 'fulltext')),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
    // 
    // var $records = array(
    //  array(
    //      'id' => 1,
    //      'model' => 'Package',
    //      'foreign_key' => 1,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"1\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"68\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"live-validate\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/1Marc\\/live-validate.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":\"\",\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/github.com\\/1Marc\\/live-validate\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"jQuery Ajax CakePHP live Validation, validates as you fill out a form.\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":\"ajax validation jquery form helper component\",\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Marc Grabanski\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"1Marc\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'live-validate',
    //      'summary' => 'jQuery Ajax CakePHP live Validation, validates as you fill out a form.',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"live-validate\",\"0\":\"1Marc\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:15',
    //      'modified' => '2011-01-19 08:07:15'
    //  ),
    //  array(
    //      'id' => 2,
    //      'model' => 'Package',
    //      'foreign_key' => 2,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"3\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"68\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"calendar-engine\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/1Marc\\/calendar-engine.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/github.com\\/1Marc\\/calendar-engine\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"Engine to fuel any JavaScript calendar-based anything.\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Marc Grabanski\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"1Marc\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'calendar-engine',
    //      'summary' => 'Engine to fuel any JavaScript calendar-based anything.',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"calendar-engine\",\"0\":\"1Marc\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:14',
    //      'modified' => '2011-01-19 08:07:14'
    //  ),
    //  array(
    //      'id' => 3,
    //      'model' => 'Package',
    //      'foreign_key' => 3,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"4\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"68\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"fisheye-menu\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/1Marc\\/fisheye-menu.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/marcgrabanski.com\\/pages\\/code\\/fisheye-menu\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"A light-weight JavaScript fisheye menu.\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Marc Grabanski\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"1Marc\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'fisheye-menu',
    //      'summary' => 'A light-weight JavaScript fisheye menu.',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"fisheye-menu\",\"0\":\"1Marc\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:15',
    //      'modified' => '2011-01-19 08:07:15'
    //  ),
    //  array(
    //      'id' => 4,
    //      'model' => 'Package',
    //      'foreign_key' => 4,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"5\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"68\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"mint-trends\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/1Marc\\/mint-trends.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/github.com\\/1Marc\\/mint-trends\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"CakePHP plugin that fetches trends from mint analytics.\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Marc Grabanski\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"1Marc\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'mint-trends',
    //      'summary' => 'CakePHP plugin that fetches trends from mint analytics.',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"mint-trends\",\"0\":\"1Marc\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:16',
    //      'modified' => '2011-01-19 08:07:16'
    //  ),
    //  array(
    //      'id' => 5,
    //      'model' => 'Package',
    //      'foreign_key' => 5,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"6\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"68\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"clean-calendar\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/1Marc\\/clean-calendar.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/marcgrabanski.com\\/pages\\/code\\/clean-calendar\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"A light-weight JavaScript calendar \\/ date picker.\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Marc Grabanski\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"1Marc\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'clean-calendar',
    //      'summary' => 'A light-weight JavaScript calendar / date picker.',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"clean-calendar\",\"0\":\"1Marc\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:15',
    //      'modified' => '2011-01-19 08:07:15'
    //  ),
    //  array(
    //      'id' => 6,
    //      'model' => 'Package',
    //      'foreign_key' => 6,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"7\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"68\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"asset-mapper\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/1Marc\\/asset-mapper.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/marcgrabanski.com\\/pages\\/code\\/asset-mapper\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"CakePHP Asset Mapper\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"1\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Marc Grabanski\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"1Marc\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'asset-mapper',
    //      'summary' => 'CakePHP Asset Mapper',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"asset-mapper\",\"0\":\"1Marc\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:13',
    //      'modified' => '2011-01-19 08:07:13'
    //  ),
    //  array(
    //      'id' => 7,
    //      'model' => 'Package',
    //      'foreign_key' => 7,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"8\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"82\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"mi\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/AD7six\\/mi.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/github.com\\/AD7six\\/mi\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"A cakephp plugin: Mi Core classes and functionality. All mi_* plugins use & require this\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Andy Dawson\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"AD7six\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'mi',
    //      'summary' => 'A cakephp plugin: Mi Core classes and functionality. All mi_* plugins use & require this',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"mi\",\"0\":\"AD7six\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:16',
    //      'modified' => '2011-01-19 08:07:16'
    //  ),
    //  array(
    //      'id' => 8,
    //      'model' => 'Package',
    //      'foreign_key' => 8,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"9\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"82\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"mi_email\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/AD7six\\/mi_email.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/github.com\\/AD7six\\/mi_email\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"A cakephp plugin:  A model based Email solution with management interface\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Andy Dawson\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"AD7six\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'mi_email',
    //      'summary' => 'A cakephp plugin:  A model based Email solution with management interface',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"mi_email\",\"0\":\"AD7six\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:16',
    //      'modified' => '2011-01-19 08:07:16'
    //  ),
    //  array(
    //      'id' => 9,
    //      'model' => 'Package',
    //      'foreign_key' => 9,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"10\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"82\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"mi_pages\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/AD7six\\/mi_pages.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/github.com\\/AD7six\\/mi_pages\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"A cakephp plugin:  A simple (in terms of code) administrative inteface for managing static pages and email templates\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Andy Dawson\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"AD7six\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'mi_pages',
    //      'summary' => 'A cakephp plugin:  A simple (in terms of code) administrative inteface for managing static pages and email templates',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"mi_pages\",\"0\":\"AD7six\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:16',
    //      'modified' => '2011-01-19 08:07:16'
    //  ),
    //  array(
    //      'id' => 10,
    //      'model' => 'Package',
    //      'foreign_key' => 10,
    //      'data' => '{\"29aa6958f92fc57f42276ea1036b47eca77703ab\":\"11\",\"217c17b9f806f8d0348d78443af4ffecf10b3722\":\"82\",\"ed4b26478c7819c6871e7bfda4cd30501db97ef5\":\"mi_tags\",\"28dd7953aa7250186d93aeb59dbffbe718a7c4d8\":\"git:\\/\\/github.com\\/AD7six\\/mi_tags.git\",\"52e9a048a74e019429d4de0fc6f2bb5d814f7fbd\":null,\"86c0b72943b51b6f5380f3b9430b024382722d3c\":\"http:\\/\\/github.com\\/AD7six\\/mi_tags\",\"6d01fb7949faf88cdb73654b697b4bd294cd6f5c\":\"A cakephp plugin: Tag anything.\",\"4ab0b830e4e6e490f34ec740987a47aa209d8069\":null,\"7fb88a5f3dc810abb99630742488ad9f048c9edc\":\"2010-02-21 00:04:35\",\"1abad699c7dde8420b0a87b0acc74b6b71a3c99d\":\"2011-01-19 04:59:07\",\"9e717bd7ed691f62b8155886ebe86124a14a54ba\":\"0\",\"2840c5c3e026dda747a88c04100584879c871082\":\"0\",\"98343315a3bb11e06389d9ba5f36165b868b991f\":\"0\",\"c85871bfb56b76a0fe7229078fe524db2eb6fe78\":\"0\",\"d2a7a298cc7c61804887c58d71cb39781b41ea75\":\"0\",\"fa25dc144ee18150abe6b225a441bb0ecbdcc4d8\":\"0\",\"26ea5784698e8d0e00932a6dcf2895670323d793\":\"0\",\"683c509477b57d143218febf0a3900beb48dbfbe\":\"0\",\"7412c9b9dd34a09e4d4c1c3d158c3fbf31568c87\":\"0\",\"abac15487a638e9350ac6b88bbae95433c4095f7\":\"0\",\"12168367ef2a1547e1c6c9527dee585e7d19f4cb\":\"0\",\"5ba43afebaaa73445cb50249e0a4dd7836eadfc9\":\"0\",\"471ca75816c019dc475a63163bd65f930341ffb6\":\"0\",\"5d623ae3309e4f535d172b64f11be53ac7c9ffe4\":\"0\",\"8d394f29097cfaa3a692770021b123c084cdd317\":\"0\",\"f0f3364b4514b664a9c0ae30b92f991216da1ba2\":\"0\",\"aea2bb0f893aaeea0bc56fd031f5528a673efbeb\":\"Andy Dawson\",\"6a2ee15641a4ef3410b79d07542c673188960b6f\":\"AD7six\",\"ff5d997eefc1a2b83855585089fc1bf5d0340ec8\":null}',
    //      'name' => 'mi_tags',
    //      'summary' => 'A cakephp plugin: Tag anything.',
    //      'url' => '{\"plugin\":null,\"controller\":\"packages\",\"action\":\"view\",\"1\":\"mi_tags\",\"0\":\"AD7six\"}',
    //      'active' => 1,
    //      'published' => NULL,
    //      'created' => '2011-01-19 08:07:16',
    //      'modified' => '2011-01-19 08:07:16'
    //  ),
    // );

}