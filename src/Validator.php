<?php

###############################################################################################################
###############################################################################################################
##                                                                                                           ##
##  ######################     #####            #####     #####            #####     ######################  ##
##  ######################     #####            #####     #####            #####     ######################  ##
##  ######################     ######           #####     #####            #####     ######################  ##
##  ######################     #######          #####     #####            #####     ######################  ##
##  ######################     ########         #####     #####            #####     ######################  ##
##  #####            #####     #########        #####     #####            #####     #####                   ##
##  #####            #####     ##########       #####     #####            #####     #####     .COM.BR       ##
##  #####            #####     ##### #####      #####     ######################     ######################  ##
##  #####            #####     #####  #####     #####     ######################     ######################  ##
##  ######################     #####   #####    #####     ######################     ######################  ##
##  ######################     #####    #####   #####     ######################     ######################  ##
##  ######################     #####     #####  #####     ######################     ######################  ##
##  ######################     #####      ##### #####     #####            #####                      #####  ##
##  ######################     #####       ##########     #####            #####                      #####  ##
##  #####                      #####        #########     #####            #####     ######################  ##
##  #####                      #####         ########     #####            #####     ######################  ##
##  #####                      #####          #######     #####            #####     ######################  ##
##  #####                      #####           ######     #####            #####     ######################  ##
##  #####                      #####            #####     #####            #####     ######################  ##
##                                                                                                           ##
###############################################################################################################
##                   TODOS OS DIREITOS RESERVADOS  O SENHOR E MEU PASTOR E NADA ME FALTARÁ                   ##
###############################################################################################################
###############################################################################################################
###############################################################################################################
##                                          INICIO CODIGO DE FONTE!                                          ##
###############################################################################################################

namespace PNHS\Validator;

/**
 * Description of validator
 *
 * @author nicolahsss
 */
class Validator {

    private $error;
    private $data;
    
    function __construct(array $data) {
        $this->data = $data;
    }

    public function rules(string $name, string $validators) {
        
        $data = trim(filter_var($this->data[$name]??null));
        return $this->validators($name, $data, $validators);
    }
    
    private function validators($name, $data, string $validators) {
        $validators_explode = \explode('|', $validators);
        foreach ($validators_explode as $value) {
            $value_explode = \explode(':', $value);
            $validator = (string) $value_explode[0];
            $option = ($value_explode[1]??'');

            $model = ValidatorFactory::build($validator);
            $model->setValue($data);
            $model->setOption($option);
            
            $result = $model->execute();
            
            if ($result === false) {
                $this->setError($name . ' ' . $model->error());
                return null;
            }
            
        }
        return $result;
    }
    
    public function errors() {
        return $this->error;
    }
    
    private function setError($error) {
        if (!is_array($this->error)) {
            $this->error = array();
        }
        
        array_push($this->error, $error);
    }

}
