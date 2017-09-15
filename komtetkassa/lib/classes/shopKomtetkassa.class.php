<?php

class shopKomtetkassa {

	public function getSuccessUrl() {

		$plugin_id = 'komtetkassa';
		$settings_name = 'komtet_success_url';
		$plugin = waSystem::getInstance()->getPlugin($plugin_id, true);
        $namespace = wa()->getApp().'_'.$plugin_id;

		$params = array(
            'namespace' => $namespace,
            'title_wrapper' => '&nbsp;%s',
            'description_wrapper' => '<span class="hint">%s</span>',
            'control_wrapper' => '%2$s'."\n".'%1$s'."\n".'%3$s'."\n",
        );

		$success_url = waHtmlControl::getControl(
            waHtmlControl::INPUT,
            'success_url',
            array_merge(
                $params,
                array(
                    'value' => $plugin->getCallbackUrl(true, "success")
                )
            )
        );

		return $success_url;

	}

	public function getFailureUrl() {

		$plugin_id = 'komtetkassa';
		$settings_name = 'komtet_failure_url';
		$plugin = waSystem::getInstance()->getPlugin($plugin_id, true);
        $namespace = wa()->getApp().'_'.$plugin_id;

		$params = array(
            'namespace' => $namespace,
            'title_wrapper' => '&nbsp;%s',
            'description_wrapper' => '<span class="hint">%s</span>',
            'control_wrapper' => '%2$s'."\n".'%1$s'."\n".'%3$s'."\n",
        );

		$failure_url = waHtmlControl::getControl(
            waHtmlControl::INPUT,
            'failure_url',
            array_merge(
                $params,
                array(
                    'value' => $plugin->getCallbackUrl(true, "failure")
                )
            )
        );

		return $failure_url;
	}

}