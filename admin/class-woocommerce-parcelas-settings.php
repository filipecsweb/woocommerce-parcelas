<?php 
/**
 * Build plugin page settings
 *
 * @since 		1.0.0
 * @author 		Filipe Seabra <eu@filipecsweb.com.br>
 * @version 	1.2.8
 */
class Woocommerce_Parcelas_Settings{
	/**
	 * Initialize option group
	 *
	 * @var 	string 	$option_group
	 */
	public $option_group = 'fswp_settings';

	/**
	 * Initialize option name
	 *
	 * @var 	string 	$option_name
	 */
	public $option_name = 'fswp_settings';

	/**
	 * Initialize page name
	 *
	 * @var 	string 	$page
	 */
	public $page = 'fswp';

	/**
	 * Initialize array of settings
	 *
	 * @var 	array 	$settings
	 */
	public $settings;

	/**
	 * Initialize array of style properties
	 *
	 * @var 	array 	$style
	 */
	public $stlye;

	public function __construct(){
		/**
		 * Add settings page menu
		 */
		add_action('admin_menu', array($this, 'fswp_admin_menu'), 100);

		/**
		 * Add the function that builds settings page content
		 */
		add_action('admin_init', array($this, 'fswp_page_settings'));

		/**
		 * Keep array of settings
		 */
		$this->settings = get_option($this->option_name);

		/**
		 * Keep array style properties
		 */
		$this->style = array(
			'color'			=>	array(
									'title'		=> 	__('Cor: ', 'woocommerce-parcelas'),
									'function' 	=> 	'fswp_text_callback',
									'class'		=> 	'color-field'
							),
			'font-weight'	=>	array(
									'title'		=> 	__('Peso: ', 'woocommerce-parcelas'),
									'function' 	=> 	'fswp_select_callback',
									'options'	=> 	array(
														'inherit'	=>	'-',
														100			=>	100,
														200			=>	200,
														300			=>	300,
														400			=>	400,
														500			=>	500,
														600			=>	600,
														700			=>	700,
														800			=>	800,
														900			=>	900
													)
							),
			'font-size'		=>	array(
									'title'			=> 	__('Tamanho: ', 'woocommerce-parcelas'),
									'function' 		=> 	'fswp_text_callback',
									'placeholder'	=>	__('Ex.: 18px ou 1.2em', 'woocommerce-parcelas')
							)								
		);
	}

	public function get_fswp_style_sections($index = null){
		$sections = array(
			0	=>	'section_style-installments_in_loop_prefix',
			1	=>	'section_style-installments_in_loop_amount',
			2	=>	'section_style-installments_in_loop_suffix',
			3	=>	'section_style-installments_in_single_prefix',
			4	=>	'section_style-installments_in_single_amount',
			5	=>	'section_style-installments_in_single_suffix',
			6	=>	'section_style-in_cash_in_loop_prefix',
			7	=>	'section_style-in_cash_in_loop_amount',
			8	=>	'section_style-in_cash_in_loop_suffix',
			9	=>	'section_style-in_cash_in_single_prefix',
			10	=>	'section_style-in_cash_in_single_amount',
			11	=>	'section_style-in_cash_in_single_suffix'
		);

		return isset($index) ? $sections[$index] : $sections;
	}

	/**
	 * Load settings page menu
	 */
	public function fswp_admin_menu(){
		add_submenu_page(
			'woocommerce',
			__('Woo Parcelas',	'woocommerce-parcelas'),
			__('Parcelas', 'woocommerce-parcelas'),
			apply_filters('fswp_page_view_permission', 'manage_options'),
			$this->page, 
			array($this, 'fswp_page_callback')
		);
	}

	/**
	 * Output the content for settings page
	 */
	public function fswp_page_callback(){
		include_once 'html-settings-page.php';
	}

	/**
	 * Create the content for settings page
	 */
	public function fswp_page_settings(){
		add_settings_field(
			'enable_installments', 
			__('Habilitar', 'woocommerce-parcelas'), 
			array($this, 'fswp_checkbox_callback'), 
			$this->page, 
			'section_general-installments', 
			array(
				'id'	=>	'enable_installments',
				'label_for'	=>	'enable_installments',
				'desc'	=>	__('Marque para ativar.', 'woocommerce-parcelas')
			)
		);

		add_settings_field(
			'installment_prefix',
			 __('Prefixo', 'woocommerce-parcelas'), 
			 array($this, 'fswp_text_callback'), 
			 $this->page, 
			 'section_general-installments', 
			 array(
			 	'id'			=>	'installment_prefix',
				'label_for'		=>	'installment_prefix',
				'default'		=>	'',
				'class'			=>	'regular-text',
				'desc'			=>	__('Escreva o texto que deve vir logo antes da quantidade. Ex.: Em at&eacute; ou Parcele em.', 'woocommerce-parcelas'),
				'placeholder'	=>	''
			 )
		);

		add_settings_field(
			'installment_qty',
			__('Quantidade de parcelas','woocommerce-parcelas'), 
			array($this, 'fswp_number_callback'), 
			$this->page, 
			'section_general-installments', 
			array(
				'id'		=>	'installment_qty',
				'label_for'	=>	'installment_qty',
				'default'	=>	2,
				'class'		=> '',
				'desc'		=>	__('Insira a quantidade de parcelas. Valor m&iacute;nimo: 2.', 'woocommerce-parcelas')
			)
		);

		add_settings_field(
			'installment_suffix', 
			__('Sufixo', 'woocommerce-parcelas'), 
			array($this, 'fswp_text_callback'), 
			$this->page, 
			'section_general-installments', 
			array(
				'id'			=>	'installment_suffix',
				'label_for'		=>	'installment_suffix',
				'default'		=>	'',
				'class'			=>	'regular-text',
				'desc'			=>	__('Escreva o texto que deve vir logo depois do pre&ccedil;o. Ex.: sem juros ou s/ juros', 'woocommerce-parcelas'),
				'placeholder'	=>	''
			)
		);

		add_settings_field(
			'installment_minimum_value', 
			__('Valor m&iacute;nimo de cada parcela', 'woocommerce-parcelas'), 
			array($this, 'fswp_text_callback'), 
			$this->page, 
			'section_general-installments', 
			array(
				'id'			=>	'installment_minimum_value',
				'label_for'		=>	'installment_minimum_value',
				'default'		=>	0,
				'class'			=> '',
				'desc'			=>	__('Caso o preço de cada parcela tenha um valor mínimo insira-o aqui. Ex.: 5 ou 5,95.<br />Use apenas separador decimal, não use separador de milhar.', 'woocommerce-parcelas'),
				'placeholder'	=>	''
			)
		);

		add_settings_field(
			'enable_in_cash', 
			__('Habilitar', 'woocommerce-parcelas'), 
			array($this, 'fswp_checkbox_callback'), 
			$this->page, 
			'section_general-in_cash', 
			array(
				'id'		=>	'enable_in_cash',
				'label_for'	=>	'enable_in_cash',
				'desc'		=>	__('Marque para ativar.', 'woocommerce-parcelas')
			)
		);

		add_settings_field(
			'in_cash_prefix',
			__('Prefixo', 'woocommerce-parcelas'),
			array($this, 'fswp_text_callback'),
			$this->page,
			'section_general-in_cash',
			array(
				'id'			=>	'in_cash_prefix',
				'label_for'		=>	'in_cash_prefix',
				'class'			=> 	'',
				'desc'			=>	__('Escreva o texto que deve vir logo antes do preço à vista. Ex.: ou', 'woocommerce-parcelas'),
				'default'		=> 	'',
				'placeholder'	=>	''
			)
		);

		add_settings_field(
			'in_cash_discount',
			__('Valor do desconto', 'woocommerce-parcelas'),
			array($this, 'fswp_text_callback'),
			$this->page,
			'section_general-in_cash',
			array(
				'id'			=>	'in_cash_discount',
				'label_for'		=>	'in_cash_discount',
				'class'			=>	'',
				'desc'			=>	__('Use apenas separador decimal, não use separador de milhar. Ex.: 4 ou 4,5.', 'woocommerce-parcelas'),
				'default'		=> 	'',
				'placeholder'	=>	''
			)
		);

		add_settings_field(
			'in_cash_discount_type',
			__('Tipo do desconto', 'woocommerce-parcelas'),
			array($this, 'fswp_select_callback'),
			$this->page,
			'section_general-in_cash',
			array(
				'id'		=>	'in_cash_discount_type',
				'options'	=>	array(
									0	=>	'%',
									1	=>	'fixo'
								),
				'desc'		=>	__('Quer descontar uma porcentagem ou um valor fixo?', 'woocommerce-parcelas'),
				'default'	=> ''
			)
		);

		add_settings_field(
			'in_cash_suffix',
			__('Sufixo', 'woocommerce-parcelas'),
			array($this, 'fswp_text_callback'),
			$this->page,
			'section_general-in_cash',
			array(
				'id'			=>	'in_cash_suffix',
				'label_for'		=>	'in_cash_suffix',
				'class'			=> 	'',
				'desc'			=>	__('Escreva o texto que deve vir logo depois do preço à vista. Ex.: à vista ou no boleto.', 'woocommerce-parcelas'),
				'default'		=> '',
				'placeholder'	=>	''
			)
		);		

		add_settings_field(
			'fswp_in_loop_position',
			__('Página que lista os produtos', 'woocommerce-parcelas'),
			array($this, 'fswp_select_callback'),
			$this->page,
			'section_position-position',
			array(
				'id'		=>	'fswp_in_loop_position',
				'options'	=>	array(
									'woocommerce_after_shop_loop_item_title' 	=> 	'Posição 1',
									'woocommerce_after_shop_loop_item' 	=> 	'Posição 2'
								),
				'desc'		=>	__('Defina a posição das parcelas, dentro das páginas que listam os produtos, e a prioridade dessa posição abaixo. Padrão: 1.', 'woocommerce-parcelas'),
				'default'	=> ''
			)
		);

		add_settings_field(
			'fswp_in_loop_position_level',
			__('Prioridade', 'woocommerce-parcelas'),
			array($this, 'fswp_number_callback'),
			$this->page,
			'section_position-position',
			array(
				'id'		=>	'fswp_in_loop_position_level',
				'label_for'	=>	'fswp_in_loop_position_level',
				'class'		=>	'fswp_position_priority',
				'default'	=>	15,
				'desc'		=>	__('Seu tema e/ou outros plugins podem disputar uma mesma posição. Use este campo para definir a prioridade para a posição das parcelas. Quanto maior o número, maior a prioridade. Padrão: 15.', 'woocommerce-parcelas')
			)
		);

		add_settings_field(
			'fswp_in_single_position',
			__('Página individual do produto', 'woocommerce-parcelas'),
			array($this, 'fswp_select_callback'),
			$this->page,
			'section_position-position',
			array(
				'id'		=>	'fswp_in_single_position',
				'options'	=>	array(
									'woocommerce_single_product_summary'	=>	'Posição 1',
									'woocommerce_before_add_to_cart_form'	=>	'Posição 2',
									'woocommerce_before_add_to_cart_button'	=>	'Posição 3',
									'woocommerce_after_add_to_cart_button'	=>	'Posição 4',
									'woocommerce_after_add_to_cart_form'	=>	'Posição 5'
								),
				'desc'		=>	__('Defina a posição das parcelas, dentro da página do produto, e a prioridade dessa posição abaixo. Padrão: 1.', 'woocommerce-parcelas'),
				'default'	=> ''
			)
		);

		add_settings_field(
			'fswp_in_single_position_level',
			__('Prioridade', 'woocommerce-parcelas'),
			array($this, 'fswp_number_callback'),
			$this->page,
			'section_position-position',
			array(
				'id'		=>	'fswp_in_single_position_level',
				'label_for'	=>	'fswp_in_single_position_level',
				'class'		=>	'fswp_position_priority',
				'default'	=>	15,
				'desc'		=>	__('Seu tema e/ou outros plugins podem disputar uma mesma posição. Use este campo para definir a prioridade para a posição das parcelas. Quanto maior o número, maior a prioridade. Padrão: 15.', 'woocommerce-parcelas')
			)
		);

		add_settings_field(
			'in_loop_alignment',
			__('Página que lista os produtos', 'woocommerce-parcelas'),
			array($this, 'fswp_select_callback'),
			$this->page,
			'section_position-alignment',
			array(
				'id'		=>	'in_loop_alignment',
				'options'	=>	array(
									'center'	=>	__('Centralizar', 'woocommerce-parcelas'),
									'right'		=>	__('Alinhar à direita', 'woocommerce-parcelas'),
									'left'		=>	__('Alinhar à esquerda', 'woocommerce-parcelas')
								),
				'default'	=>	''
			)
		);

		add_settings_field(
			'in_single_alignment',
			__('Página individual do produto', 'woocommerce-parcelas'),
			array($this, 'fswp_select_callback'),
			$this->page,
			'section_position-alignment',
			array(
				'id'		=>	'in_single_alignment',
				'options'	=>	array(
									'left'		=>	__('Alinhar à esquerda', 'woocommerce-parcelas'),
									'right'		=>	__('Alinhar à direita', 'woocommerce-parcelas'),
									'center'	=>	__('Centralizar', 'woocommerce-parcelas')
								),
				'default'	=>	''
			)
		);

		foreach($this->get_fswp_style_sections() as $k => $section){
			foreach($this->style as $prop => $data){
				$id = explode('-', $section);

				add_settings_field(
					$id[1]."_".$prop,
					$data['title'],
					array($this, $data['function']),
					$this->page,
					$section,
					array(
						'id'			=>	$id[1]."_".$prop,
						'label_for'		=>	$id[1]."_".$prop,
						'class'			=> 	isset($data['class']) ? $data['class'] : '',
						'options'		=>	isset($data['options']) ? $data['options'] : '',
						'placeholder'	=>	isset($data['placeholder']) ? $data['placeholder'] : '',
						'default'		=> 	isset($data['default']) ? $data['default'] : ''
					)
				);
			}			
		}		

		register_setting(
			$this->option_group, 
			$this->option_name, 
			array($this, 'fswp_options_sanitize')
		);
	}

	public function fswp_checkbox_callback($args){
		extract($args);

		$value = isset($this->settings[$id]) ? $this->settings[$id] : 0;

		echo "<input type='checkbox' id='".$id."-0' name='".$this->option_name."[$id]' value='1'".checked('1', $value, false)." />";
		echo isset($desc) ? "<span class='description'> $desc</span>" : '';
	}

	public function fswp_text_callback($args){
		extract($args);

		$value = isset($this->settings[$id]) ? $this->settings[$id] : $default;

		echo "<input class='$class' type='text' id='$id' name='".$this->option_name."[$id]' value='$value' placeholder='$placeholder' />";
		echo isset($desc) ? "<br /><span class='description'>$desc</span>" : '';
	}

	public function fswp_number_callback($args){
		extract($args);

		$value = isset($this->settings[$id]) ? $this->settings[$id] : $default;
		
		echo "<input class='$class' type='number' id='$id' name='".$this->option_name."[$id]' value='$value' />";
		echo isset($desc) ? "<br /><span class='description'>$desc</span>" : '';
	}

	public function fswp_select_callback($args){
		extract($args);

		$value = isset($this->settings[$id]) ? $this->settings[$id] : $default;

		echo "<select name='".$this->option_name."[$id]'>";
		foreach($options as $k => $option){
			echo "<option value='$k'".selected($k, $value, false).">".$option."</option>";
		}
		echo "</select>";
		echo isset($desc) ? "<br /><span class='description'>$desc</span>" : '';
	}

	public function fswp_options_sanitize($input){
		foreach($input as $k => $v){
			if($k == 'installment_qty'){
				if($v < 2 || empty($v)){
					$v = 2;
				}
			}
			else if($k == 'installment_minimum_value'){
				if($v < 0 || empty($v)){
					$v = 0;
				}
			}

			$newinput[$k] = trim($v);			
		}

		return $newinput;
	}
}

new Woocommerce_Parcelas_Settings();