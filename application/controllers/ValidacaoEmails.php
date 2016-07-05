<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ValidacaoEmails extends CI_Controller {

	/*
   * Método construtor do controller
	 */
	public function __construct()
	{
		parent::__construct();

		// Carregamento da classe Mailboxlayer
		include APPPATH.'third_party/Mailboxlayer.php';
	}

	/*
   * Método responsável por exibir a view e chamar a validação do email
   * caso tenha sido executado um POST do formulário
	 */
	public function Index()
	{
		$dados = null;

		// Se foi feito o POST do formulário, então executa o processo de validação
		if($this->input->post('email')){
			$dados['validEmail'] = $this->ValidaEmail($this->input->post('email'));
		}

		$this->load->view('home',$dados);
	}

	/*
	 * Método para validação do endereço de email
	 * com o auxílio da API do MailBoxLayer
	 *
	 * @param string $email Email a ser validado
	 *
	 * @return array
	 */
	public function ValidaEmail($email)
	{
		//Instancia a classe Mailboxlayer
		$mailboxlayer = new Mailboxlayer();

		//Variável que irá receber as mensagen relacionadas ao processo de validação
		$mensagens = array();

		//Processo de validação do email
		if( $mailboxlayer->verifyMail($email) === false ){
			array_push($mensagens,'Ocorreu um erro no processamento -> ['.$mailboxlayer->errorCode.'] '.$mailboxlayer->errorText);
		}else{

			// Verifica se o formato do email é válido
			if( empty($mailboxlayer->response->format_valid ) ){
				array_push($mensagens,'O formato do email é inválido.');
			}

			// Retorna uma sugestão para o endereço do email
			if( !empty($mailboxlayer->response->did_you_mean) ){
				array_push($mensagens, 'Sugestão de endereço correto: '.$mailboxlayer->response->did_you_mean);
			}

			// Verifica o registro MX
			if( empty($mailboxlayer->response->mx_found) ){
				if( $mailboxlayer->response->mx_found === null ){
					array_push($mensagens,'Registro MX não verificado.');
				}else{
					array_push($mensagens,'Registro MX não encontrado.');
				}
			}

			// Verifica o servidor SMTP
			if( empty($mailboxlayer->response->smtp_check) ){
				if( $mailboxlayer->response->smtp_check === null ){
					array_push($mensagens,'Servidor SMTP não verificado.');
				}else{
					array_push($mensagens,'Servidor SMTP não encontrado.');
				}
			}

			// Converte a qualidade do endereço de email para um número inteiro,
			// uma vez que o valor retornado é entre 0 e 1
			$qualidadeEmail = $mailboxlayer->response->score * 100;

			//Sugestão de qualidade do endereço de email
			if( $qualidadeEmail < 33 ){
				array_push($mensagens,'A qualidade do endereço é ruim.');
			}elseif( $qualidadeEmail < 65 ){
				array_push($mensagens,'A qualidade do endereço é razoável.');
			}else{
				array_push($mensagens,'A qualidade do endereço é boa.');
			}
		}

		// Retorna o array com as mensagens geradas no processo de validação
		return $mensagens;
	}
}
