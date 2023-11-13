<?php

namespace WcParcelas\App\Common;

defined( 'ABSPATH' ) || exit;

class MetaBox {
	public string $fswp_post_meta_key = 'fswp_post_meta';

	public string $disable_installments_key = 'disable_installments';

	public string $installment_qty_key = 'installment_qty';

	public string $disable_in_cash_key = 'disable_in_cash';

	public string $in_cash_discount_type_key = 'in_cash_discount_type';

	public string $in_cash_discount_key = 'in_cash_discount';

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'add_fswp_product_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_fswp_product_meta_box' ] );
	}

	public function add_fswp_product_meta_box() {
		add_meta_box(
			'fswp_product_meta_box',
			__( 'Parcelamento e Pagamento à Vista', 'wc-parcelas' ),
			[ $this, 'fswp_product_meta_box_callback' ],
			'product',
			'normal',
			'low'
		);
	}

	public function fswp_product_meta_box_callback() {
		wp_nonce_field( 'fswp_product_meta_box_nonce_context', 'fswp_product_meta_box_nonce_name' );

		$disable_installments  = $this->get_fswp_post_meta_data( $this->disable_installments_key ) ?? '0';
		$installment_qty       = $this->get_fswp_post_meta_data( $this->installment_qty_key ) ?? '';
		$disable_in_cash       = $this->get_fswp_post_meta_data( $this->disable_in_cash_key ) ?? '0';
		$in_cash_discount      = $this->get_fswp_post_meta_data( $this->in_cash_discount_key ) ?? '';
		$in_cash_discount_type = $this->get_fswp_post_meta_data( $this->in_cash_discount_type_key );
        ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th><?php echo __( 'Opções de Parcelamento', 'wc-parcelas' ) ?></th>
            </tr>

            <tr>
                <td>
                    <label for='<?php echo "$this->fswp_post_meta_key[$this->disable_installments_key]" ?>'>
						<?php _e( 'Desativar preço parcelado para este produto?', 'wc-parcelas' ) ?>
                    </label>
                </td>
                <td>
                    <select id='<?php echo "$this->fswp_post_meta_key[$this->disable_installments_key]" ?>'
                            name='<?php echo "$this->fswp_post_meta_key[$this->disable_installments_key]" ?>'
                    >
                        <option value='0' <?php echo selected( $disable_installments, '0' ) ?>><?php _e( 'Não', 'wc-parcelas' ) ?></option>
                        <option value='1' <?php echo selected( $disable_installments, '1' ) ?>><?php _e( 'Sim', 'wc-parcelas' ) ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <label for='<?php echo "$this->fswp_post_meta_key[$this->installment_qty_key]" ?>'>
			            <?php _e( 'Quantidade de parcelas', 'wc-parcelas' ) ?>
                    </label>
                </td>
                <td>
                    <input type='number'
                           id='<?php echo "$this->fswp_post_meta_key[$this->installment_qty_key]" ?>'
                           name='<?php echo "$this->fswp_post_meta_key[$this->installment_qty_key]" ?>'
                           value='<?php echo $installment_qty ?>'
                           min="1"
                    />
                    <p class="description">
                        <?php _e( 'Insira a quantidade de parcelas. Valor mínimo: 2.', 'wc-parcelas' ) ?><br>
                        <?php _e( 'Deixe em branco para usar o valor global.', 'wc-parcelas' ) ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th><?php echo __( 'Opções de Pagamento à Vista', 'wc-parcelas' ) ?></th>
            </tr>

            <tr>
                <td>
                    <label for='<?php echo "$this->fswp_post_meta_key[$this->disable_in_cash_key]" ?>'>
			            <?php _e( 'Desativar preço à vista para este produto?', 'wc-parcelas' ) ?>
                    </label>
                </td>
                <td>
                    <select id='<?php echo "$this->fswp_post_meta_key[$this->disable_in_cash_key]" ?>'
                            name='<?php echo "$this->fswp_post_meta_key[$this->disable_in_cash_key]" ?>'
                    >
                        <option value='0' <?php echo selected( $disable_in_cash, '0' ) ?>><?php _e( 'Não', 'wc-parcelas' ) ?></option>
                        <option value='1' <?php echo selected( $disable_in_cash, '1' ) ?>><?php _e( 'Sim', 'wc-parcelas' ) ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <label for='<?php echo "$this->fswp_post_meta_key[$this->in_cash_discount_key]" ?>'>
			            <?php _e( 'Valor do desconto', 'wc-parcelas' ) ?>
                    </label>
                </td>
                <td>
                    <input type='text'
                           id='<?php echo "$this->fswp_post_meta_key[$this->in_cash_discount_key]" ?>'
                           name='<?php echo "$this->fswp_post_meta_key[$this->in_cash_discount_key]" ?>'
                           value='<?php echo $in_cash_discount ?>'
                   />
                    <p class="description">
                        <?php _e( 'Use apenas separador decimal, não use separador de milhar. Ex.: 4 ou 4,5.', 'wc-parcelas' ) ?><br>
                        <?php _e( 'Deixe em branco para usar o valor global.', 'wc-parcelas' ) ?>
                    </p>
                </td>
            </tr>

            <tr>
                <td>
                    <label for='<?php echo "$this->fswp_post_meta_key[$this->in_cash_discount_type_key]" ?>'>
			            <?php _e( 'Tipo do desconto', 'wc-parcelas' ) ?>
                    </label>
                </td>
                <td>
                    <select id='<?php echo "$this->fswp_post_meta_key[$this->in_cash_discount_type_key]" ?>'
                            name='<?php echo "$this->fswp_post_meta_key[$this->in_cash_discount_type_key]" ?>'
                    >
                        <option value='default'><?php _e( 'Padrão', 'wc-parcelas' ) ?></option>
                        <option value='0' <?php echo selected( $in_cash_discount_type, '0' ) ?>>%</option>
                        <option value='1' <?php echo selected( $in_cash_discount_type, '1' ) ?>><?php _e( 'fixo', 'wc-parcelas' ); ?></option>
                    </select>
                    <p class="description">
		                <?php _e( 'Quer descontar uma porcentagem ou um valor fixo?', 'wc-parcelas' ) ?><br>
		                <?php _e( 'Mantenha "Padrão" para usar o valor global.', 'wc-parcelas' ) ?>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	/**
	 * @param  string $field Field name.
	 * @return mixed|null
	 */
	public function get_fswp_post_meta_data( $field ) {
		$post_meta = (array) get_post_meta( get_the_ID(), $this->fswp_post_meta_key, true );
		if (
			! isset( $post_meta[ $field ] ) ||
			'' === $post_meta[ $field ]
		) {
			return null;
		}

		return $post_meta[ $field ];
	}

	public function save_fswp_product_meta_box( $post_id ) {
		if (
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			! isset( $_POST['fswp_product_meta_box_nonce_name'] ) ||
			! wp_verify_nonce( $_POST['fswp_product_meta_box_nonce_name'], 'fswp_product_meta_box_nonce_context' )
		) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if ( isset( $_POST[ $this->fswp_post_meta_key ] ) && is_array( $_POST[ $this->fswp_post_meta_key ] ) ) {
			foreach ( $_POST[ $this->fswp_post_meta_key ] as $k => $value ) {
				switch ( $k ) {
					case $this->disable_installments_key:
					case $this->installment_qty_key:
					case $this->disable_in_cash_key:
					case $this->in_cash_discount_key:
						$_POST[ $this->fswp_post_meta_key ][ $k ] = trim( sanitize_text_field( $value ) );
						break;
					case $this->in_cash_discount_type_key:
						$_POST[ $this->fswp_post_meta_key ][ $k ] = trim( sanitize_text_field( $value ) ) === 'default' ? null : trim( sanitize_text_field( $value ) );
						break;
				}
			}

			update_post_meta( $post_id, $this->fswp_post_meta_key, $_POST[ $this->fswp_post_meta_key ] );
		}
	}
}