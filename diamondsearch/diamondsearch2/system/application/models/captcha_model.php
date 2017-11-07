  <?php
     class Captcha_model extends Model
    {
    function Captcha_model()
    {
    parent::Model();
    }

    function generateCaptcha()
    {
          $this->load->library('captchalib');
        
        $expiration = time()-300; // Two hour limit
        $this->db->query("DELETE FROM ".$this->config->item('table_perfix')."captcha WHERE captcha_time < ".$expiration);
        $vals = array(
                   // 'word'         => 'XYX ZA',
                    'img_path'     => './uploads/captcha/',
                    'img_url'     => $this->config->item('base_url').'uploads/captcha/',
                    'font_path'     => './system/fonts/font2.ttf',
                    'img_width'     => '155',
                    'img_height' => '35',
                    'expiration' => '3600'
                );

        $cap = $this->captchalib->create_captcha($vals);
        //var_dump($cap['time']);
        
        $dati['src']= $cap['src'];
        //mette nel db
        $data = array(
                    'captcha_id'    => '',
                    'captcha_time'  => $cap['time'],
                    'ip_address'    => $this->input->ip_address(),
                    'word'          => $cap['word']
                );

        $query = $this->db->insert_string($this->config->item('table_perfix').'captcha', $data);
        $this->db->query($query);
//            if($srconly) return  $dati['src'];
//		      else echo $dati['src'];
		 return  $dati['src'];
            
        
    }
    }
    
    ?>