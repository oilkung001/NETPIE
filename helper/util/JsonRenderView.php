<?php
class JsonRenderView extends \Slim\View {
    public $encodingOptions = 0;
    public $contentType = 'application/json';
    public function __construct(){
        parent::__construct();
    }
    public function render($status = 200, $data = NULL) {
        $app = \Slim\Slim::getInstance();
        $status = intval($status);
        $response = $this->all();
        unset($response['flash']);
        if($status>=400 && !isset($response['error'])){
            $response['error'] = array(
              'code'=>0,
              'message'=>'unknown error',
            );
        }
        if(count($response) == 0){
          $response = new stdClass;
        }
        $app->response()->status($status);
        $app->response()->header('Content-Type', $this->contentType);
        $jsonp_callback = $app->request->get('callback', null);
        if($jsonp_callback !== null){
            $app->response()->body($jsonp_callback.'('.json_encode($response, $this->encodingOptions).')');
        } else {
            $app->response()->body(json_encode($response, $this->encodingOptions));
        }
        $app->stop();
    }
}
