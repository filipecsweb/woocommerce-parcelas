=== WooCommerce Parcelas ===

Contributors: filiprimo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BPVZJZ2WG8ZPG
Tags: parcelas, parcelamento, pagamento, payment, installments, woocommerce, parcela produto variavel, à vista, preço boleto, preço à vista, in cash
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.2.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

<p>Com este plugin é possível adicionar informações sobre preço parcelado e/ou preço para pagamento à vista, nas páginas que listam todos os produtos e na página individual de cada produto.</p>
<p>Possui uma página de opções em "WooCommerce" > "Parcelas" onde é possível definir:</p>
<ul>
	<li>Opções para parcelamento:
		<ul>
			<li>Prefixo;</li>
			<li>Quantidade de parcelas;</li>
			<li>Sufixo;</li>
			<li>Valor mínimo da parcela.</li>
		</ul>
	</li>
	<li>Opções para pagamento à vista:
		<ul>
			<li>Prefixo;</li>
			<li>Valor do desconto;</li>
			<li>Tipo do desconto (% ou fixo);</li>
			<li>Sufixo;</li>
			<li>É possível desabilitar o preço à vista e/ou o preço parcelado em qualquer produto, individualmente.</li>

		</ul>
	</li>
	<li>Posicão e alinhamento dessas informações dentro das páginas;</li>
	<li>Estilizar as informações de parcelamento e de pagamento à vista, separadamente e por página.</li>
</ul>
<p>As imagens falam por si mesmas: <a href="https://wordpress.org/plugins/woocommerce-parcelas/screenshots/">WooCommerce Parcelas Screenshots</a>.</p>
<p><strong>Compatível, também, com produto variável e agrupado.</strong></p>

== Installation ==

1. Faça o upload do plugin através do painel do WordPress, indo em "Plugins" > "Adicionar Novo";
2. Ative o plugin indo até o menu "Plugins";
3. Habilite a funcionalidade do plugin e defina suas opções em "WooCommerce" > "Parcelas".

== Frequently Asked Questions ==

= Funciona com o ponto como separador decimal? =

Sim.

= Funciona com produto variável e agrupado? =

Sim.

= É possível definir um preço para pagamento à vista (boleto)? =

Sim.

= Quero adicionar juros, é possível? =

Ainda não, talvez, em breve...

<hr />
<p><a href="http://filipecsweb.com.br/contato" target="_blank">Bugs e Sugestões</a></p>

== Screenshots ==

1. Tela de configuração (Geral)
2. Tela de configuração (Posição)
3. Páginas que listam os produtos (frontend)
4. Página indivudual do produto (frontend)
5. Página indivudual do produto (backend)

== Changelog ==

= 1.2.9 =
* Declaração "!important" foi adicionada aos valores das propriedades em CSS;
* A função depreciada get_product() foi substituída por wc_get_product();
* O domínio de texto 'woocommerce-parcelas' foi alterado para 'wc-parcelas'.

= 1.2.8 =
* Corrigido bug que impedia o preço à vista de ser mostrado, em produtos variáveis com preços diferentes, caso o parcelamento estivesse desativado.
* Adicionada opção para desabilitar o preço parcelado em produtos específicos.
* Adicionada opção para mudar a posição de alinhamento (centralizar) das informações.
* Adicionada sessão com opções que possibilitam a estilização/formatação das informações.
* Corrigida URL do botão 'Bugs e Sugestões'

= 1.2.7 =
* Adicionada opção para desabilitar o preço à vista em produtos específicos.

= 1.2.6 =
* Corrigido compatibilidade com produto agrupado. Adicionada opção para definir a posição do preço parcelado e à vista. Adicionada opção para definir um valor de desconto para pagamentos à vista.

= 1.2.5.3 =
* Settings link below plugin name was fixed.

= 1.2.5.1 =
* Minimum value, with comma, wasn't working in variable product.

= 1.2.5 =
* Some improvements habe been mave in code.

= 1.2.4 =
* Some bugs were fixed. Some actions were added. One filter was added. Better css classes were added.

= 1.2.3 =
* js code was fixed to work correctly when decimal separator is point.

= 1.2.2 =
* Fully compatible with variable product.

= 1.2.1 =
* Translated to english.

= 1.2 =
* Installment minimum value field added.

= 1.1 =
* Prefix and suffix fields added.

= 1.0 =
* Plugin release.

== Upgrade Notice ==

= 1.2.9 =
* Atualize com tranquilidade para a última versão do WooCommerce Parcela. Mantenha seu plugin WooCommerce sempre atualizado com a última versão.

= 1.2.8 =
* Esta versão vem com uma série de correções, otimizações de código e novas opções, atualize sem medo!

= 1.2.7 =
* Agora você pode desabilitar o preço à vista em produtos específicos.

= 1.2.6 =
* WooCommerce Parcelas agora muito mais completo! Atualize para definir a posição do preço parcelado e definir um desconto para pagamentos à vista (boleto).

= 1.2.5.1 =
* Fixed some bugs.

= 1.2.5 =
* The plugin code was rewrited to work better.

= 1.2.4 =
* Now you can customize the plugin using some actions.

= 1.2.3 =
* Now the plugin works with point, as decimal separator.

= 1.2.2 =
* For those who wants to displays correct installments in variable product, now the plugin is fully compatible with that product type.

= 1.2.1 =
* The plugin is now completely translated to en_US.

= 1.2 =
* Now you can define an installment minimum value. PagSeguro, for example, set it to 5.