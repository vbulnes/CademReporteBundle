<?php

namespace Cadem\ReporteBundle\Twig;

use Symfony\Component\Process\Process;

class RstExtension extends \Twig_Extension
{
	public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('rst2html', array($this, 'rst2htmlFilter')),
        );
    }
	
	public function rst2htmlFilter($rst)
    {
		if(file_exists('/usr/bin/rst2html')){
			// --initial-header-level=3 to begin titles at the h3 tag
			$process = new Process('rst2html --no-doc-title --initial-header-level=3');
			$process->setStdin($rst);
			$process->run();
			$html = $process->getOutput();

			$startpos = strpos($html, '<body>') + 6 + 23;
			$endpos   = strpos($html, '</body>') - 7;
			
			return substr($html, $startpos, $endpos - $startpos);
		}
		else return $rst;
    }

    public function getName()
    {
        return 'rst_extension';
    }


}
