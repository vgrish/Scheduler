<?php
/**
 * Class sProcessorTask
 */
class sProcessorTask extends sTask
{
    /**
     * @param array $data
     * @return mixed
     */
    public function _run(array $data = array())
    {
        $action = $this->get('content');
        $path = $this->getOption('core_path') . 'model/modx/processors/';
        $data['task'] =& $this;

        $namespace = $this->_getNamespace();
        if ($namespace && $namespace->name != 'core') {
            $path = $namespace->getCorePath() . 'processors/';
        }

        /** @var modProcessorResponse $response */
        $response = $this->xpdo->runProcessor($action, $data, array(
            'processors_path' => $path,
        ));
        if ($response->isError()) {
            $errors = $response->getFieldErrors();
            /** @var modProcessorResponseError $error */
            foreach ($errors as $error) {
                $this->addError($error->field, $error->error);
            }
        }
        return $response->getMessage();
    }
}