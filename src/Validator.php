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
class Validator
{

    private $error;
    private $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function rules(string $name, string $validators)
    {

        if (is_array($this->data)) {
            $data = ($this->data[$name] ?? null);
        } else {
            $data = ($this->data ?? null);
        }
        return $this->validators($name, $data, $validators);
    }

    private function validators($name, $data, string $validators)
    {
        $validators_explode = \explode('|', $validators);
        foreach ($validators_explode as $value) {

            $value_hash = \explode('#', $value);
            $value_option = \explode(':', $value_hash[0]);
            $validator = (string) $value_option[0];
            $option = ($value_option[1] ?? '');
            $code = ($value_hash[1] ?? '');

            $model = ValidatorFactory::build($validator);
            $model->setValue($data);
            $model->setOption($option);
            $model->setCode((int) $code);

            $result = $model->execute();

            if ($result === false) {
                $this->setError($name, $name . ' ' . $model->error(), $model->code());
                return null;
            }
        }
        return $result;
    }

    public function errors()
    {
        return $this->error;
    }

    private function setError($name, $error, $code)
    {
        if (!is_array($this->error)) {
            $this->error = array();
        }

        array_push($this->error, [
            'parameter' => $name,
            'error' => $error,
            'code' => $code
        ]);
    }
}
