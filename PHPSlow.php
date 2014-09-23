<?php

// +----------------------------------------------------------------------
// | [phpslow-reader]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-9-16 13:29
// +----------------------------------------------------------------------
// + PHPSlow.php
// +----------------------------------------------------------------------

class PHPSlow
{
    private $execCache = array();
    private $linesLimit = 100;
    private $config = array();
    private $repetitions = array();

    /**
     * @param array $config config array, for example:
     *          array(
     *              'servers' => array(
     *                  'localhost' => array(
     *                      // In this server, 'host' field is unset, so it can be recognized as 'localhost'.
     *                      'file' => '/var/log/php/php-slow.log'
     *                  ),
     *                  'Server1' => array(
     *                      'host' => '192.168.2.38',
     *                      'file' => '/tmp/php-slow.log'
     *                  ),
     *                  'ServerB' => array(
     *                      'host' => '192.168.2.39',
     *                      'file' => '/tmp/php-slow.log'
     *                  )
     *                  ...
     *              )
     *          )
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->linesLimit = $config['linesLimit'] > 0 ? $config['linesLimit'] : 100;
    }

    /**
     * Get content from specific file between two line number.
     *
     * @param string $serverName
     * @param string $filename
     * @param int $start
     * @param int $end
     * @return string
     */
    private function getFileLines($serverName, $filename, $start, $end)
    {
        $server = $this->config['servers'][$serverName];
        if (!$server) {
            return '';
        }
        $command = "sed -n '{$start},{$end}p' {$filename}";
        if (isset($server['host'])) {
            $command = "ssh root@{$server['host']} \"{$command}\"";
        }
        $cacheKey = md5($command);
        if (isset($this->execCache[$cacheKey])) {
            $outputs = $this->execCache[$cacheKey];
        } else {
            exec($command, $outputs);
            $this->execCache[$cacheKey] = $outputs;
        }
//        array_splice($outputs, $end - $start - 1);
        return implode("\n", $outputs);
    }

    /**
     * Get last 'line number' lines content from specific file.
     *
     * @param $serverName
     * @param $filename
     * @param $lines
     * @return string
     */
    private function getFileLastLines($serverName, $filename, $lines)
    {
        $server = $this->config['servers'][$serverName];

        $command = "tail -{$lines} {$filename}";
        if (isset($server['host'])) {
            $command = "ssh root@{$server['host']} \"{$command}\"";
        }
        exec($command, $outputs);

        return implode("\n", $outputs);
    }

    /**
     * Get traces array from specific server.
     *
     * @param $serverName
     * @return array
     */
    public function getTraces($serverName)
    {
        $logFileName = $this->config['servers'][$serverName]['file'];

        $logs = $this->getFileLastLines($serverName, $logFileName, $this->linesLimit);
        $logs = preg_split('/\n{2,}/', $logs);
        $tracesArr = array();
        $trace_key = 0;

        foreach ($logs as $_k => $log) {
            $log = trim($log);
            if (!$log || !preg_match('/^\[\d+-[a-zA-Z]+-\d+ \d+:\d+:\d+\]/i', $log)) {
                continue;
            }
            $log = trim($log);
            $_md5 = md5(preg_replace('/\[[\w]{18}\]/i', '', substr($log, strpos($log, "\n") + 1)));
            if (isset($this->repetitions[$_md5])) {
                $repetition_key = $this->repetitions[$_md5];
                $tracesArr[$repetition_key]['repetitions'] += 1;
                continue;
            }
            $log = preg_split('/\n+/', $log);
            if (!$log) {
                continue;
            }

            $traces = array();

            $title = array_shift($log);
            $traces['repetitions'] = 1;

            $traces['time'] = date('Y-m-d H:i:s', strtotime(trim(substr($title, 0, 22), '[]')));
            $traces['title'] = substr($title, 22);
            $traces['script'] = str_replace('script_filename = ', '', array_shift($log));
            foreach ($log as $_log) {

                @list(, $trace['function'], $trace['fileAndLine']) = preg_split('/\s+/', $_log);
                if (!$trace) {
                    continue;
                }
                unset($_log);
                @list($trace['file'], $trace['line']) = preg_split('/:/', $trace['fileAndLine']);

                if (is_file($trace['file'])) {
                    $fragment_start = $trace['line'] - 10;
                    $fragment_end = $trace['line'] + 10;
                    if ($trace['line'] <= 10) {
                        $fragment_start = 1;
                        $fragment_end = 20;
                    }
                    $trace['code_fragment_start_line'] = $fragment_start;
                    $trace['code_fragment'] = htmlspecialchars(
                        $this->getFileLines(
                            $serverName,
                            $trace['file'],
                            $fragment_start,
                            $fragment_end
                        )
                    );
                }

                $traces['traces'][] = $trace;
                unset($trace);
            }
            $_trace_key = $trace_key++;
            $this->repetitions[$_md5] = $_trace_key;
            $tracesArr[$_trace_key] = $traces;
            unset($traces);
        }
        $tracesArr = array_reverse($tracesArr);
        $ret = array(
            'server' => $serverName,
            'file' => $logFileName,
            'traces' => $tracesArr
        );
        unset($tracesArr);
        return $ret;
    }
} 