<?php
namespace ElasticEmail;

class ElasticEmail_Messages {
    public function __construct(ElasticEmail $master) {
        $this->master = $master;
    }


    public function send($message) {

        $_params = 'username='.$this->master->emailAccount.
      			'&api_key='.$this->master->apikey.
      			'&from='.urlencode($message['from_email']).
      			'&from_name='.urlencode($message['from_name']).
      			'&to='.urlencode($message['to'][0]['email']).
      			'&subject='.urlencode($message['subject']);

      	if(isset($message['body_html'])){ $_params .= '&body_html='.urlencode($message['body_html']); }
        if(isset($message['body_text'])){ $_params .= '&body_text='.urlencode($message['body_text']); }

        return $this->master->call('mailer/send', $_params);
    }


    public function sendTemplate($template_name, $template_content, $message) {


        $_params = 'username='.$this->master->emailAccount.
      			'&api_key='.$this->master->apikey.
      			'&from='.urlencode($message['from_email']).
      			'&from_name='.urlencode($message['from_name']).
      			'&to='.urlencode($message['to'][0]['email']).
      			'&subject='.urlencode($message['subject']).
            '&template='.$template_name;


      	$_params .= '&body_html='.urlencode($template_content);
      	$_params .= '&body_text='.urlencode($template_content);


        return $this->master->call('mailer/send', $_params);
    }


}
