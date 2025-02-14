<?php

namespace Synaptic4U\Packages\Journal\JournalList;

use Synaptic4U\Core\Log;
use Synaptic4U\Packages\Journal\ConfigJournal\Model as ConfigJournalModel;
use Synaptic4U\Packages\Journal\Journal\Journal;
use Synaptic4U\Packages\Journal\Journal\Model as JournalModel;
use Synaptic4U\Packages\Journal\Journal\View as JournalView;

class JournalList
{
    // Returns the JournalList list
    public function load($params)
    {
        
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params, JSON_PRETTY_PRINT),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        // Checks if there are journal entries for the selected user $data = array['cnt'] ? 0 : > 0
        $data = $mod->checklist($params);

        if (null === $data) {
            return null;
        }

        // Checks if there are journal entries for the selected user if there aren't any it enters the if statement
        if ((isset($data['cnt'])) && (0 === ((int) $data['cnt']))) {
            $data = null;

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            $mod = new ConfigJournalModel();

            if (null === $mod) {
                return null;
            }

            // First check the users journal sections to see if there is any
            $data = $mod->checklist($params);

            if (null === $data) {
                return null;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            // If there are no configurations for journal we catch it and redirect to Journal Config.
            if (isset($data['cnt']) && (0 === (int) $data['cnt'])) {
                $this->log([
                    'Location' => __METHOD__.'()',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);

                $mod = new JournalModel();

                if (null === $mod) {
                    return null;
                }

                $calls = $mod->callsCreate($params);

                if (null === $calls) {
                    return null;
                }

                $view = new JournalView($params, $data, $calls);

                if (null === $view) {
                    return null;
                }

                return $view->empty();
            }

            // If there are configurations for Journal we redirect to add a new journal
            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            $journal = new Journal($params);

            $this->log([
                'Location' => __METHOD__.'()',
                '$journal' => serialize($journal),
            ]);

            return $journal->create($params);
        }

        // There are journal entries for the selected user
        // Loads list of journals
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params, JSON_PRETTY_PRINT),
            'data' => json_encode($data, JSON_PRETTY_PRINT),
        ]);

        /**
         * Fetches the users name & surname
         * Adds encrypted @userid as a param to list
         * Fetches a list of journal entry dates.
         *
         * @return array
         *               [0] = user name & surname
         *               [1] = encrypted userid
         *               [2>] = journal entry dates
         */
        $data = $mod->loadlist($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsLoad($params);

        if (null === $calls) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params, JSON_PRETTY_PRINT),
            'data' => json_encode($data, JSON_PRETTY_PRINT),
            'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        ]);

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->showlist();
    }

    public function pagination($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->pagination($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsPagination($params);

        if (null === $calls) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'data' => json_encode($data),
            'params' => json_encode($params),
            'calls' => json_encode($calls),
        ]);

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->pagination();
    }

    public function loadShared($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->loadShared($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsLoad($params);

        if (null === $calls) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
            'data' => json_encode($data),
            'calls' => json_encode($calls),
        ]);

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        if (isset($data['count']) && (0 === (int) $data['count'])) {
            return $view->empty();
        }

        return $view->showlist();
    }

    public function paginationShared($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->paginationShared($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsPagination($params);

        if (null === $calls) {
            return null;
        }

        $this->log([
            'Location' => __METHOD__.'()',
            'data' => json_encode($data),
            'params' => json_encode($params),
            'calls' => json_encode($calls),
        ]);

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->pagination();
    }

    protected function log($msg){
        new Log($msg);
    }
}