<?php

class GF_Mailcheck_Validator {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_filter( 'gform_field_validation', [ $this, 'validate_email_domain' ], 10, 4 );
	}

	public function enqueue_assets(): void {
		if ( is_admin() ) {
			return;
		}

		/*
		* Mailcheck.js from CDN.
		* You can download the library locally and change this address if necessary.
		*/
		wp_enqueue_script(
			'mailcheck',
			plugin_dir_url( __FILE__ ) . 'assets/js/mailcheck.min.js',
			[],
			'1.1.1',
			true
		);

		wp_enqueue_script(
			'gf-mailcheck-validator',
			plugin_dir_url( __FILE__ ) . 'assets/js/gf-mailcheck-validator.js',
			[ 'mailcheck' ],
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/gf-mailcheck-validator.js' ),
			true
		);

		wp_localize_script(
			'gf-mailcheck-validator',
			'GFMailcheckSettings',
			[
				'language' => $this->get_current_language(),
				'domains'  => $this->get_valid_domains(),
				'messages' => $this->get_messages(),
			]
		);

		wp_enqueue_style(
			'gf-mailcheck-validator',
			plugin_dir_url( __FILE__ ) . 'assets/css/gf-mailcheck-validator.css',
			[],
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/gf-mailcheck-validator.css' )
		);
	}


	/**
	 * Returns the supported language code based on the WordPress locale.
	 */
	private function get_current_language(): string {
		$locale   = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
		$language = strtolower( substr( (string) $locale, 0, 2 ) );
		$allowed  = [ 'pl', 'en', 'cs', 'de', 'it', 'lt', 'lv', 'sk', 'uk', 'ro', 'et', 'hu', 'fr', 'es' ];

		return in_array( $language, $allowed, true ) ? $language : 'en';
	}

	/**
	 * Messages used by JavaScript and server validation.
	 *
	 * @return array<string,array<string,string>>
	 */
	private function get_messages(): array {
		return [
			'pl' => [
				'suggestion' => 'Czy chodziło Ci o %s?',
				'blocked'    => 'Popraw adres e-mail przed wysłaniem formularza.',
				'validation' => 'Adres zawiera prawdopodobną literówkę w domenie „%1$s”. Czy chodziło Ci o „%2$s”?',
			],
			'en' => [
				'suggestion' => 'Did you mean %s?',
				'blocked'    => 'Correct the email address before submitting the form.',
				'validation' => 'The email address probably contains a typo in the domain “%1$s”. Did you mean “%2$s”?',
			],
			'cs' => [
				'suggestion' => 'Měli jste na mysli %s?',
				'blocked'    => 'Před odesláním formuláře opravte e-mailovou adresu.',
				'validation' => 'E-mailová adresa pravděpodobně obsahuje překlep v doméně „%1$s“. Měli jste na mysli „%2$s“?',
			],
			'de' => [
				'suggestion' => 'Meinten Sie %s?',
				'blocked'    => 'Korrigieren Sie die E-Mail-Adresse, bevor Sie das Formular absenden.',
				'validation' => 'Die E-Mail-Adresse enthält wahrscheinlich einen Tippfehler in der Domain „%1$s“. Meinten Sie „%2$s“?',
			],
			'it' => [
				'suggestion' => 'Intendevi %s?',
				'blocked'    => 'Correggi l’indirizzo e-mail prima di inviare il modulo.',
				'validation' => 'L’indirizzo e-mail contiene probabilmente un errore nel dominio “%1$s”. Intendevi “%2$s”?',
			],
			'lt' => [
				'suggestion' => 'Ar turėjote omenyje %s?',
				'blocked'    => 'Prieš pateikdami formą pataisykite el. pašto adresą.',
				'validation' => 'El. pašto adreso domene „%1$s“ tikriausiai yra klaida. Ar turėjote omenyje „%2$s“?',
			],
			'lv' => [
				'suggestion' => 'Vai domājāt %s?',
				'blocked'    => 'Pirms veidlapas nosūtīšanas izlabojiet e-pasta adresi.',
				'validation' => 'E-pasta adreses domēnā “%1$s” iespējams ir drukas kļūda. Vai domājāt “%2$s”?',
			],
			'sk' => [
				'suggestion' => 'Mali ste na mysli %s?',
				'blocked'    => 'Pred odoslaním formulára opravte e-mailovú adresu.',
				'validation' => 'E-mailová adresa pravdepodobne obsahuje preklep v doméne „%1$s“. Mali ste na mysli „%2$s“?',
			],
			'uk' => [
				'suggestion' => 'Ви мали на увазі %s?',
				'blocked'    => 'Виправте адресу електронної пошти перед надсиланням форми.',
				'validation' => 'Адреса електронної пошти, ймовірно, містить помилку в домені «%1$s». Ви мали на увазі «%2$s»?',
			],
			'ro' => [
				'suggestion' => 'Ați vrut să scrieți %s?',
				'blocked'    => 'Corectați adresa de e-mail înainte de a trimite formularul.',
				'validation' => 'Adresa de e-mail conține probabil o greșeală în domeniul „%1$s”. Ați vrut să scrieți „%2$s”?',
			],
			'et' => [
				'suggestion' => 'Kas mõtlesite %s?',
				'blocked'    => 'Parandage e-posti aadress enne vormi saatmist.',
				'validation' => 'E-posti aadressi domeenis „%1$s” on tõenäoliselt kirjaviga. Kas mõtlesite „%2$s”?',
			],
			'hu' => [
				'suggestion' => 'Erre gondolt: %s?',
				'blocked'    => 'Az űrlap elküldése előtt javítsa ki az e-mail-címet.',
				'validation' => 'Az e-mail-cím „%1$s” domainjében valószínűleg elírás van. Erre gondolt: „%2$s”?',
			],
			'fr' => [
				'suggestion' => 'Vouliez-vous dire %s ?',
				'blocked'    => 'Corrigez l’adresse e-mail avant d’envoyer le formulaire.',
				'validation' => 'L’adresse e-mail contient probablement une faute dans le domaine « %1$s ». Vouliez-vous dire « %2$s » ?',
			],
			'es' => [
				'suggestion' => '¿Quisiste decir %s?',
				'blocked'    => 'Corrige la dirección de correo electrónico antes de enviar el formulario.',
				'validation' => 'La dirección de correo probablemente contiene un error en el dominio «%1$s». ¿Quisiste decir «%2$s»?',
			],
		];
	}

	/**
	 * List of valid, popular domains used by Mailcheck.js.
	 *
	 * @return string[]
	 */
	private function get_valid_domains(): array {
		$domains = [
			// Google
			'gmail.com',
			'googlemail.com',

			// Microsoft
			'outlook.com',
			'outlook.pl',
			'hotmail.com',
			'hotmail.co.uk',
			'hotmail.fr',
			'hotmail.de',
			'live.com',
			'live.co.uk',
			'msn.com',

			// Apple
			'icloud.com',
			'me.com',
			'mac.com',

			// Yahoo
			'yahoo.com',
			'yahoo.pl',
			'yahoo.co.uk',
			'yahoo.de',
			'yahoo.fr',
			'yahoo.it',
			'yahoo.es',

			// Proton
			'proton.me',
			'protonmail.com',
			'pm.me',

			// Poland
			'wp.pl',
			'o2.pl',
			'onet.pl',
			'op.pl',
			'interia.pl',
			'interia.eu',
			'poczta.fm',
			'gazeta.pl',

			// Germany
			'gmx.de',
			'gmx.net',
			'web.de',
			't-online.de',
			'freenet.de',

			// The czech republic
			'seznam.cz',
			'email.cz',
			'centrum.cz',
			'volny.cz',

			// Slovakia
			'azet.sk',
			'centrum.sk',
			'post.sk',

			// Italy
			'libero.it',
			'virgilio.it',
			'tiscali.it',

			// France
			'orange.fr',
			'free.fr',
			'laposte.net',
			'sfr.fr',

			// Spain
			'telefonica.net',
			'orange.es',

			// Romania
			'yahoo.ro',

			// Lithuania
			'gmail.lt',
			'inbox.lt',

			// Latvia.
			'inbox.lv',

			// Estonia
			'hot.ee',
			'mail.ee',

			// Hungary
			'freemail.hu',
			'citromail.hu',

			// Ukraine
			'ukr.net',
			'i.ua',
			'meta.ua',
			'bigmir.net',
		];

		return apply_filters( 'gf_mailcheck_valid_domains', array_values( array_unique( $domains ) ) );
	}

	/**
	 * Map of the most obvious typos also blocked on the PHP side.
	 *
	 * @return array<string,string>
	 */
	private function get_domain_corrections(): array {
		$corrections = [
			// Gmail
			'gmai.com'        => 'gmail.com',
			'gmai.co'         => 'gmail.com',
			'gmai.con'        => 'gmail.com',
			'gmial.com'       => 'gmail.com',
			'gimal.com'       => 'gmail.com',
			'gamil.com'       => 'gmail.com',
			'gmail.co'        => 'gmail.com',
			'gmail.con'       => 'gmail.com',
			'gmail.cm'        => 'gmail.com',
			'gmail.om'        => 'gmail.com',
			'gmail.cim'       => 'gmail.com',
			'gmail.comm'      => 'gmail.com',
			'gmaill.com'      => 'gmail.com',
			'gmal.com'        => 'gmail.com',
			'gnail.com'       => 'gmail.com',
			'gmali.com'       => 'gmail.com',
			'gmaul.com'       => 'gmail.com',
			'gmaol.com'       => 'gmail.com',
			'gmailcom'        => 'gmail.com',
			'gmail.pl'        => 'gmail.com',
			'gmeil.com'        => 'gmail.com',

			// Outlook
			'outlok.com'      => 'outlook.com',
			'outloo.com'      => 'outlook.com',
			'outllok.com'     => 'outlook.com',
			'outlook.co'      => 'outlook.com',
			'outlook.con'     => 'outlook.com',
			'outlook.cm'      => 'outlook.com',
			'outlookcom'      => 'outlook.com',
			'outlok.pl'       => 'outlook.pl',

			// Hotmail
			'hotmal.com'      => 'hotmail.com',
			'hotmai.com'      => 'hotmail.com',
			'hotmil.com'      => 'hotmail.com',
			'hotmial.com'     => 'hotmail.com',
			'hotmail.co'      => 'hotmail.com',
			'hotmail.con'     => 'hotmail.com',
			'hotmail.cm'      => 'hotmail.com',
			'hotmailcom'      => 'hotmail.com',

			// iCloud
			'iclod.com'       => 'icloud.com',
			'icoud.com'       => 'icloud.com',
			'icloid.com'      => 'icloud.com',
			'iclould.com'     => 'icloud.com',
			'icloud.co'       => 'icloud.com',
			'icloud.con'      => 'icloud.com',

			// Yahoo
			'yaho.com'        => 'yahoo.com',
			'yahooo.com'      => 'yahoo.com',
			'yaoo.com'        => 'yahoo.com',
			'yhoo.com'        => 'yahoo.com',
			'yahoo.co'        => 'yahoo.com',
			'yahoo.con'       => 'yahoo.com',
			'yahoo.cm'        => 'yahoo.com',

			// Proton
			'protonmai.com'   => 'protonmail.com',
			'protonmal.com'   => 'protonmail.com',
			'protonmail.co'   => 'protonmail.com',
			'protonmail.con'  => 'protonmail.com',
			'proton.ne'       => 'proton.me',

			// Poland.
			'wpp.pl'          => 'wp.pl',
			'wp.p'            => 'wp.pl',
			'wp.com.pl'       => 'wp.pl',

			'o2.p'            => 'o2.pl',
			'02.pl'           => 'o2.pl',
			'o2.com.pl'       => 'o2.pl',

			'onte.pl'         => 'onet.pl',
			'one.pl'          => 'onet.pl',
			'onet.p'          => 'onet.pl',
			'onet.com.pl'     => 'onet.pl',

			'intera.pl'       => 'interia.pl',
			'interia.p'       => 'interia.pl',
			'interia.com.pl'  => 'interia.pl',

			'poczt.fm'        => 'poczta.fm',
			'poczta.f'        => 'poczta.fm',

			// The czech republic
			'seznam.c'        => 'seznam.cz',
			'seznam.czs'      => 'seznam.cz',
			'seznma.cz'       => 'seznam.cz',

			// Slovakia
			'azet.s'          => 'azet.sk',
			'azte.sk'         => 'azet.sk',

			// Germany
			'gmx.d'           => 'gmx.de',
			'gmz.de'          => 'gmx.de',
			'web.d'           => 'web.de',

			// Ukraine
			'ukr.ne'          => 'ukr.net',
			'ukr.nt'          => 'ukr.net',
			'urk.net'         => 'ukr.net',
		];

		return apply_filters( 'gf_mailcheck_domain_corrections', $corrections );
	}


	/**
	 * Checks whether the field is handled by the new validator.
	 */
	private function uses_new_email_validator( $field ): bool {
		if ( ! $field ) {
			return false;
		}

		$css_class = isset( $field->cssClass ) ? (string) $field->cssClass : '';

		return (bool) preg_match(
			'/(?:^|\s)pwe-email-validate(?:\s|$)/',
			$css_class
		);
	}

	/**
	* Gravity Forms server validation.
	*
	* JavaScript provides a convenient suggestion, but this part really
	* prevents saving the address with the detected typo.
	*/
	public function validate_email_domain( $result, $value, $form, $field ) {
		if ( ! $field || 'email' !== $field->get_input_type() ) {
			return $result;
		}

		// Fields marked with pwe-email-validate are handled by the new addon.
		if ( $this->uses_new_email_validator( $field ) ) {
			return $result;
		}

		if ( ! $result['is_valid'] || empty( $value ) || ! is_string( $value ) ) {
			return $result;
		}

		$email = strtolower( sanitize_email( $value ) );

		if ( ! is_email( $email ) ) {
			return $result;
		}

		$at_position = strrpos( $email, '@' );

		if ( false === $at_position ) {
			return $result;
		}

		$domain      = substr( $email, $at_position + 1 );
		$corrections = $this->get_domain_corrections();

		if ( isset( $corrections[ $domain ] ) ) {
			$language = $this->get_current_language();
			$messages = $this->get_messages();
			$template = $messages[ $language ]['validation'] ?? $messages['en']['validation'];

			$result['is_valid'] = false;
			$result['message']  = sprintf(
				$template,
				esc_html( $domain ),
				esc_html( $corrections[ $domain ] )
			);
		}

		return $result;
	}
}
