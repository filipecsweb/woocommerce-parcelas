<?php

namespace WcParcelas\App\Common;

defined( 'ABSPATH' ) || exit;

/**
 * Service class for settings page.
 */
class Settings {
	public string $option_group = 'fswp_settings';

	public string $option_name = 'fswp_settings';

	public string $page = 'fswp';

	public array $style = [];

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'fswp_admin_menu' ], 100 );
		add_action( 'admin_init', [ $this, 'fswp_page_settings' ] );

		$this->style = array(
			'color'       => array(
				'title'    => __( 'Cor: ', 'wc-parcelas' ),
				'function' => 'fswp_text_callback',
				'class'    => 'color-field'
			),
			'font-weight' => array(
				'title'    => __( 'Peso: ', 'wc-parcelas' ),
				'function' => 'fswp_select_callback',
				'options'  => array(
					'inherit' => '-',
					100       => 100,
					200       => 200,
					300       => 300,
					400       => 400,
					500       => 500,
					600       => 600,
					700       => 700,
					800       => 800,
					900       => 900
				)
			),
			'font-size'   => array(
				'title'       => __( 'Tamanho: ', 'wc-parcelas' ),
				'function'    => 'fswp_text_callback',
				'placeholder' => __( 'Ex.: 18px ou 1.2em', 'wc-parcelas' )
			)
		);
	}

	/**
	 * @return array
	 */
	public function getOptions() {
		static $options = null;
		if ( ! isset( $options ) ) {
			$options = (array) get_option( $this->option_name, [] );
		}

		return $options;
	}

	/**
	 * @param  string $name
	 * @return mixed|null
	 */
	public function getOption( string $name ) {
		static $option = [];
		if ( ! isset( $option[ $name ] ) ) {
			$options         = $this->getOptions();
			$option[ $name ] = $options[ $name ] ?? null;
		}

		return $option[ $name ];
	}

	/**
	 * Load settings page menu
	 */
	public function fswp_admin_menu() {
		add_submenu_page(
			'woocommerce',
			__( 'WooCommerce Parcelas', 'wc-parcelas' ),
			__( 'Parcelas', 'wc-parcelas' ),
			apply_filters( 'fswp_page_view_permission', 'manage_options' ),
			$this->page,
			[ $this, 'fswp_page_callback' ]
		);
	}

	/**
	 * Output the content for settings page
	 */
	public function fswp_page_callback() {
		include_once WC_PARCELAS_PATH . 'public/views/settings-page.php';
	}

	/**
	 * Create the content for settings page
	 */
	public function fswp_page_settings() {
		$settings_fields = array(
			array(
				'id'       => 'enable_installments',
				'title'    => __( 'Habilitar?', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_checkbox_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-installments',
				'args'     => array(
					'id'        => 'enable_installments',
					'label_for' => 'enable_installments',
					'desc'      => __( 'Marque para ativar', 'wc-parcelas' )
				)
			),
			array(
				'id'       => 'installment_prefix',
				'title'    => __( 'Prefixo', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_text_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-installments',
				'args'     => array(
					'id'          => 'installment_prefix',
					'label_for'   => 'installment_prefix',
					'default'     => '',
					'class'       => 'regular-text',
					'desc'        => __( 'Escreva o texto que deve vir logo antes da quantidade. Ex.: Em até; ou Parcele em.', 'wc-parcelas' ),
					'placeholder' => ''
				)
			),
			array(
				'id'       => 'installment_qty',
				'title'    => __( 'Quantidade de parcelas', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_number_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-installments',
				'args'     => array(
					'id'        => 'installment_qty',
					'label_for' => 'installment_qty',
					'default'   => 2,
					'desc'      => __( 'Insira a quantidade de parcelas. Valor mínimo: 2.', 'wc-parcelas' )
				)
			),
			array(
				'id'       => 'installment_suffix',
				'title'    => __( 'Sufixo', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_text_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-installments',
				'args'     => array(
					'id'          => 'installment_suffix',
					'label_for'   => 'installment_suffix',
					'default'     => '',
					'class'       => 'regular-text',
					'desc'        => __( 'Escreva o texto que deve vir logo depois do preço. Ex.: sem juros ou s/ juros', 'wc-parcelas' ),
					'placeholder' => ''
				)
			),
			array(
				'id'       => 'installment_minimum_value',
				'title'    => __( 'Valor mínimo de cada parcela', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_text_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-installments',
				'args'     => array(
					'id'          => 'installment_minimum_value',
					'label_for'   => 'installment_minimum_value',
					'default'     => 0,
					'desc'        => __( 'Caso o preço de cada parcela tenha um valor mínimo insira-o aqui. Ex.: 5 ou 5,95.<br />Use apenas separador decimal, não use separador de milhar.', 'wc-parcelas' ),
					'placeholder' => ''
				)
			),
			array(
				'id'       => 'enable_installments_if_out_of_stock',
				'title'    => __( 'Habilitar nos produtos fora de estoque?', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_select_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-installments',
				'args'     => array(
					'id'      => 'enable_installments_if_out_of_stock',
					'options' => array(
						'no'  => 'Não',
						'yes' => 'Sim',
					),
					'default' => 'no'
				)
			),
			array(
				'id'       => 'enable_in_cash',
				'title'    => __( 'Habilitar?', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_checkbox_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-in_cash',
				'args'     => array(
					'id'        => 'enable_in_cash',
					'label_for' => 'enable_in_cash',
					'desc'      => __( 'Marque para ativar', 'wc-parcelas' )
				)
			),
			array(
				'id'       => 'in_cash_prefix',
				'title'    => __( 'Prefixo', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_text_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-in_cash',
				'args'     => array(
					'id'          => 'in_cash_prefix',
					'label_for'   => 'in_cash_prefix',
					'class'       => 'regular-text',
					'desc'        => __( 'Escreva o texto que deve vir logo antes do preço à vista. Ex.: ou', 'wc-parcelas' ),
					'default'     => '',
					'placeholder' => ''
				)
			),
			array(
				'id'       => 'in_cash_discount',
				'title'    => __( 'Valor do desconto', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_text_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-in_cash',
				'args'     => array(
					'id'          => 'in_cash_discount',
					'label_for'   => 'in_cash_discount',
					'desc'        => __( 'Use apenas separador decimal, não use separador de milhar. Ex.: 4 ou 4,5.', 'wc-parcelas' ),
					'default'     => '',
					'placeholder' => ''
				)
			),
			array(
				'id'       => 'in_cash_discount_type',
				'title'    => __( 'Tipo do desconto', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_select_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-in_cash',
				'args'     => array(
					'id'      => 'in_cash_discount_type',
					'options' => array(
						0 => '%',
						1 => __('fixo', 'wc-parcelas')
					),
					'desc'    => __( 'Quer descontar uma porcentagem ou um valor fixo?', 'wc-parcelas' ),
					'default' => ''
				)
			),
			array(
				'id'       => 'in_cash_suffix',
				'title'    => __( 'Sufixo', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_text_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-in_cash',
				'args'     => array(
					'id'          => 'in_cash_suffix',
					'label_for'   => 'in_cash_suffix',
					'class'       => 'regular-text',
					'desc'        => __( 'Escreva o texto que deve vir logo depois do preço à vista. Ex.: à vista ou no boleto.', 'wc-parcelas' ),
					'default'     => '',
					'placeholder' => ''
				)
			),
			array(
				'id'       => 'fswp_in_loop_position',
				'title'    => __( 'Página que lista os produtos', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_select_callback' ],
				'page'     => $this->page,
				'section'  => 'section_position-position',
				'args'     => array(
					'id'      => 'fswp_in_loop_position',
					'options' => array(
						'woocommerce_after_shop_loop_item_title' => 'Abaixo do Título do Produto',
						'woocommerce_after_shop_loop_item'       => 'Abaixo do Produto'
					),
					'desc'    => __( 'Defina a posição das parcelas, dentro das páginas que listam os produtos, e a prioridade dessa posição abaixo. Padrão: 1.', 'wc-parcelas' ),
					'default' => ''
				)
			),
			array(
				'id'       => 'fswp_in_loop_position_level',
				'title'    => __( 'Prioridade', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_number_callback' ],
				'page'     => $this->page,
				'section'  => 'section_position-position',
				'args'     => array(
					'id'        => 'fswp_in_loop_position_level',
					'label_for' => 'fswp_in_loop_position_level',
					'default'   => 15,
					'desc'      => __( 'Seu tema e/ou outros plugins podem disputar uma mesma posição. Use este campo para definir a prioridade para a posição das parcelas. Quanto maior o número, maior a prioridade. Padrão: 15.', 'wc-parcelas' )
				)
			),
			array(
				'id'       => 'fswp_in_single_position',
				'title'    => __( 'Página individual do produto', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_select_callback' ],
				'page'     => $this->page,
				'section'  => 'section_position-position',
				'args'     => array(
					'id'      => 'fswp_in_single_position',
					'options' => array(
						'woocommerce_single_product_summary'    => 'Na descrição curta do produto',
						'woocommerce_before_add_to_cart_form'   => 'Acima do campo adicionar ao carrinho',
						'woocommerce_before_add_to_cart_button' => 'Acima do botão adicionar ao carrinho',
						'woocommerce_after_add_to_cart_button'  => 'Abaixo do botão adicionar ao carrinho',
						'woocommerce_after_add_to_cart_form'    => 'Abaixo do campo adicionar ao carrinhoo'
					),
					'desc'    => __( 'Defina a posição das parcelas, dentro da página do produto, e a prioridade dessa posição abaixo. Padrão: 1.', 'wc-parcelas' ),
					'default' => ''
				)
			),
			array(
				'id'       => 'fswp_in_single_position_level',
				'title'    => __( 'Prioridade', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_number_callback' ],
				'page'     => $this->page,
				'section'  => 'section_position-position',
				'args'     => array(
					'id'        => 'fswp_in_single_position_level',
					'label_for' => 'fswp_in_single_position_level',
					'default'   => 15,
					'desc'      => __( 'Seu tema e/ou outros plugins podem disputar uma mesma posição. Use este campo para definir a prioridade para a posição das parcelas. Quanto maior o número, maior a prioridade. Padrão: 15.', 'wc-parcelas' )
				)
			),
			array(
				'id'       => 'in_loop_alignment',
				'title'    => __( 'Página que lista os produtos', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_select_callback' ],
				'page'     => $this->page,
				'section'  => 'section_position-alignment',
				'args'     => array(
					'id'      => 'in_loop_alignment',
					'options' => array(
						'center' => __( 'Centralizar', 'wc-parcelas' ),
						'right'  => __( 'Alinhar à direita', 'wc-parcelas' ),
						'left'   => __( 'Alinhar à esquerda', 'wc-parcelas' )
					),
					'default' => ''
				)
			),
			array(
				'id'       => 'in_single_alignment',
				'title'    => __( 'Página individual do produto', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_select_callback' ],
				'page'     => $this->page,
				'section'  => 'section_position-alignment',
				'args'     => array(
					'id'      => 'in_single_alignment',
					'options' => array(
						'left'   => __( 'Alinhar à esquerda', 'wc-parcelas' ),
						'right'  => __( 'Alinhar à direita', 'wc-parcelas' ),
						'center' => __( 'Centralizar', 'wc-parcelas' )
					),
					'default' => ''
				)
			),
			array(
				'id'       => 'enable_in_cash_if_out_of_stock',
				'title'    => __( 'Habilitar nos produtos fora de estoque?', 'wc-parcelas' ),
				'callback' => [ $this, 'fswp_select_callback' ],
				'page'     => $this->page,
				'section'  => 'section_general-in_cash',
				'args'     => array(
					'id'      => 'enable_in_cash_if_out_of_stock',
					'options' => array(
						'no'  => 'Não',
						'yes' => 'Sim',
					),
					'default' => 'no'
				)
			),
		);

		foreach ( $settings_fields as $settings_field ) {
			add_settings_field( $settings_field['id'], $settings_field['title'], $settings_field['callback'], $settings_field['page'], $settings_field['section'], $settings_field['args'] );
		}

		foreach ( $this->get_fswp_style_sections() as $section ) {
			foreach ( $this->style as $prop => $data ) {
				$id = explode( '-', $section );

				add_settings_field(
					$id[1] . "_" . $prop,
					$data['title'],
					[ $this, $data['function'] ],
					$this->page,
					$section,
					array(
						'id'          => $id[1] . "_" . $prop,
						'label_for'   => $id[1] . "_" . $prop,
						'class'       => $data['class'] ?? '',
						'options'     => $data['options'] ?? '',
						'placeholder' => $data['placeholder'] ?? '',
						'default'     => $data['default'] ?? ''
					)
				);
			}
		}

		register_setting(
			$this->option_group,
			$this->option_name,
			[ $this, 'fswp_options_sanitize' ]
		);
	}

	public function get_fswp_style_sections( $index = null ) {
		$sections = array(
			0  => 'section_style-installments_in_loop_prefix',
			1  => 'section_style-installments_in_loop_amount',
			2  => 'section_style-installments_in_loop_suffix',
			3  => 'section_style-installments_in_single_prefix',
			4  => 'section_style-installments_in_single_amount',
			5  => 'section_style-installments_in_single_suffix',
			6  => 'section_style-in_cash_in_loop_prefix',
			7  => 'section_style-in_cash_in_loop_amount',
			8  => 'section_style-in_cash_in_loop_suffix',
			9  => 'section_style-in_cash_in_single_prefix',
			10 => 'section_style-in_cash_in_single_amount',
			11 => 'section_style-in_cash_in_single_suffix'
		);

		return isset( $index ) ? $sections[ $index ] : $sections;
	}

	public function fswp_checkbox_callback( $args ) {
		/**
		 * @var $id
		 */
		extract( $args );

		$value = wcParcelas()->settings->getOption( $id ) ? wcParcelas()->settings->getOption( $id ) : 0;

		echo "<label for='$id-0'>";
		echo "<input type='checkbox' id='", $id, "-0' name='", $this->option_name, "[$id]' value='1'", checked( '1', $value, false ), " />";
		echo $desc ?? '';
		echo "</label>";
	}

	public function fswp_text_callback( $args ) {
		/**
		 * @var $id
		 * @var $class
		 * @var $default
		 * @var $placeholder
		 */
		extract( $args );

		$value = wcParcelas()->settings->getOption( $id ) ? wcParcelas()->settings->getOption( $id ) : $default;

		echo "<input class='", $class ?? '', "' type='text' id='$id' name='", $this->option_name, "[$id]' value='$value' placeholder='$placeholder' />";
		echo isset( $desc ) ? "<p class='description'>$desc</p>" : '';
	}

	public function fswp_number_callback( $args ) {
		/**
		 * @var $id
		 * @var $class
		 * @var $default
		 */
		extract( $args );

		$value = wcParcelas()->settings->getOption( $id ) ? wcParcelas()->settings->getOption( $id ) : $default;

		echo "<input class='", $class ?? '', "' type='number' id='$id' name='", $this->option_name, "[$id]' value='$value' />";
		echo isset( $desc ) ? "<p class='description'>$desc</p>" : '';
	}

	public function fswp_select_callback( $args ) {
		/**
		 * @var $id
		 * @var $default
		 * @var $options
		 */
		extract( $args );

		$value = wcParcelas()->settings->getOption( $id ) ? wcParcelas()->settings->getOption( $id ) : $default;

		echo "<select class='", $class ?? '', "' name='", $this->option_name, "[$id]'>";
		foreach ( $options as $k => $option ) {
			echo "<option value='$k'" . selected( $k, $value, false ) . ">" . $option . "</option>";
		}
		echo "</select>";
		echo isset( $desc ) ? "<p class='description'>$desc</p>" : '';
	}

	public function fswp_options_sanitize( $input ) {
		$new_input = array();

		foreach ( $input as $k => $v ) {
			if ( $k == 'installment_qty' ) {
				if ( $v < 2 || empty( $v ) ) {
					$v = 2;
				}
			} elseif ( $k == 'installment_minimum_value' ) {
				if ( $v < 0 || empty( $v ) ) {
					$v = 0;
				}
			}

			$new_input[ $k ] = trim( $v );
		}

		return $new_input;
	}
}