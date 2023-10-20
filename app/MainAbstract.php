<?php

namespace WcParcelas\App;

defined( 'ABSPATH' ) || exit;

abstract class MainAbstract {
	public ?\WcParcelas\App\Common\Utils $utils = null;

	public ?\WcParcelas\App\Common\Settings $settings = null;

	public ?\WcParcelas\App\Common\MetaBox $metaBox = null;

	public ?\WcParcelas\App\Common\FrontEnd $frontEnd = null;
}