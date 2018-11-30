<? require_once $_SERVER["DOCUMENT_ROOT"] . "/eVote/shared/bases/modules/email/class.phpmailer.php" ; ?>
<?
class EmailSender {
    protected $mailInfo = null;
    protected $userName = "풀링폴링 고객센터";
    protected $userEmail = "pullingpolling@gmail.com";
    protected $password = "dgudgu2018";
    protected $host = "smtp.gmail.com";
    protected $port = 465;
    protected $mailer = "smtp";

    function EmailSender(){
        $this->init();
    }

    function init()
    {
        $this->mailInfo = new PHPMailer();
        $this->mailInfo->IsSMTP();
        $this->mailInfo->Host = $this->host;
        $this->mailInfo->Port = $this->port;
        $this->mailInfo->Mailer = $this->mailer;
        $this->mailInfo->SMTPAuth = true;
        $this->mailInfo->SMTPSecure = 'ssl';
        $this->mailInfo->WordWrap = 50;
        $this->mailInfo->IsHTML(true);
        $this->setAccount($this->userEmail, $this->password);
        $this->setSendEmail($this->userEmail, $this->userName);
    }

    function setAccount($email, $pass){
        $this->mailInfo->Username = $email; // SMTP username
        $this->mailInfo->Password = $pass; // SMTP password
    }

    function addReceiveEmail($email, $name){
        $this->mailInfo->addAddress($email,$name);
    }

    function setSendEmail($email, $name){
        $this->mailInfo->From = $email;
        $this->mailInfo->FromName = $name;
    }

    function isReplyAvail(){
        $this->mailInfo->AddReplyTo($this->userEmail, $this->userName);
    }

    function attachFile($filePath){
        $this->mailInfo->AddAttachment($filePath);
    }

    function setMailBody($content){
        $this->mailInfo->Body = $content;
    }

    function setSubject($subject){
        $this->mailInfo->Subject = $subject;
    }


    function sendTestMail(){
        $this->setMailBody("테스트 이메일");
        $this->setSubject("테스트 이메일 제목");
        $this->addReceiveEmail("yjham2002@gmail.com", "dasf");
        return $this->sendMail();
    }

    function sendMailTo($title, $msg, $addr, $addrName){
        $this->setMailBody($msg);
        $this->setSubject($title);
        $this->addReceiveEmail($addr, $addrName);
        return $this->sendMail();
    }


    function sendMail(){
        $isSend = $this->mailInfo->Send();
        return $isSend;
    }
}

?>