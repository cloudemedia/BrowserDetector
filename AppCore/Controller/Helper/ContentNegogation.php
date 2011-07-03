<?php
declare(ENCODING = 'iso-8859-1');
namespace AppCore\Controller\Helper;

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Simplify AJAX context switching based on requested format
 *
 * @uses       \Zend\Controller\Action\Helper\AbstractHelper
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ContentNegogation extends \Zend\Controller\Action\Helper\ContextSwitch
{
    /**
     * headers which are possibliy added as html header into the content
     * 
     * @var array
     */
    private $_headers = array(
        'content-language' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'content-type' => array('placement' => 'PREPEND', 'keyType' => 'http-equiv'),
        'content-length' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'content-style-type' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'content-script-type' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'expires' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'pragma' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'refresh' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'reply-to' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'cache-control' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
        'last-modified' => array('placement' => 'APPEND', 'keyType' => 'http-equiv'),
    );
    
    /**
     * a flag to tell that HTML5 should be used instead of HTML4/XHTML1
     * 
     * @var boolean
     */
    private $_useHtmlFive = false;

    /**
     * a flag to tell that XHTML1 should be used instead of HTML4
     * 
     * @var boolean
     */
    private $_useXhtml = false;

    /**
     * Save default encoding code.
     *
     * @var string
     */
    private $_defaultEncoding;

    /**
     * HTTP_ACCEPT_CHARSET
     * 
     * @var array
     */
    private $_acceptEncoding = array();

    /**
     * the encodings which are wished to deliver
     * 
     * @var array
     */
    private $_wantedEncoding = array();

    /**
     * Save default content type.
     *
     * @var string
     */
    private $_defaultType;

    /**
     * HTTP_ACCEPT
     * 
     * @var array
     */
    private $_acceptType = array();

    /**
     * the content type which is wished to deliver
     * 
     * @var array
     */
    private $_wantedType = array();
    
    /**
     * a flag to tell the browser properties should be used instead of the 
     * user definition
     * 
     * @var boolean
     */
    private $_preferBrowser = true;
    
    /**
     * an \Zend\Layout\Layout instance 
     * 
     * @var \Zend\Layout\Layout
     */
    private $_layout = null;
    
    /**
     * an \Zend\Log\Logger instance 
     * 
     * @var \Zend\Log\Logger
     */
    private $_logger = null;
    
    /**
     * Constructor
     *
     * Add HTML context
     *
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $isAjax = $this->getRequest()->isXmlHTTPRequest();
        
        /*
         * the supported content types
         * TODO: check if different suffixes for ajax and normal htnl content 
         *       are needed
         */
        $this->addContexts(array(
            'xhtml'  => array(
                'suffix'    => ($isAjax ? 'xajax' : 'xhtml'),
                'headers'   => array('Content-Type' => 'application/xhtml+xml'),
                'callbacks' => array(
                    'post' => 'postHtmlContext'
                )
            ),
            'xhtmlmp'  => array(
                'suffix'    => ($isAjax ? 'xajaxmp' : 'xhtmlmp'),
                'headers'   => array('Content-Type' => 'application/vnd.wap.xhtml+xml'),
                'callbacks' => array(
                    'post' => 'postHtmlContext'
                )
            ),
            'html'  => array(
                'suffix'    => ($isAjax ? 'ajax' : 'html'),
                'headers'   => array('Content-Type' => 'text/html'),
                'callbacks' => array(
                    'post' => 'postHtmlContext'
                )
            ),
            'wap'  => array(
                'suffix'    => 'wap',
                'headers'   => array('Content-Type' => 'text/vnd.wap.wml'),
                'callbacks' => array(
                    'post' => 'postHtmlContext'
                )
            ),
            'rss'  => array(
                'suffix'    => 'rss',
                'headers'   => array('Content-Type' => 'application/rss+xml')
            ),
            'atom'  => array(
                'suffix'    => 'atom',
                'headers'   => array('Content-Type' => 'application/atom+xml')
            ),
            'soap'  => array(
                'suffix'    => 'soap',
                'headers'   => array('Content-Type' => 'application/soap+xml')
            ),
            'css'  => array(
                'suffix'    => 'css',
                'headers'   => array('Content-Type' => 'text/css')
            ),
            'js'  => array(
                'suffix'    => 'js',
                'headers'   => array('Content-Type' => 'text/javascript')
            ),
            'pdf'  => array(
                'suffix'    => 'pdf',
                'headers'   => array('Content-Type' => 'application/pdf'),
                'callbacks' => array(
                    'post' => 'postPdfContext'
                )
            ),
            'excel5'  => array(
                'suffix'    => 'excel5',
                'headers'   => array('Content-Type' => 'application/vnd.ms-excel'),
                'callbacks' => array(
                    'post' => 'postExcelContext'
                )
            ),
            'excel2007'  => array(
                'suffix'    => 'excel2007',
                'headers'   => array('Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                'callbacks' => array(
                    'post' => 'postExcelContext'
                )
            )
        ));
    }

    /**
     * HTML post processing
     *
     * JSON serialize view variables to response body
     *
     * @return void
     */
    public function postHtmlContext()
    {
        /*
         * tranform headers from response into html meta elements
         */
        $response = $this->getResponse();
        $headers  = $response->getHeaders();
        $view     = $this->getActionController()->view;
        
        $encoding = \Zend\Registry::get('_encoding');
        
        if ($view->doctype()->isHtml5()) {
            $view->headMeta()->setCharset($encoding);
        }
        
        foreach ($headers as $header) {
            $headerName  = strtolower($header['name']);
            $headerType  = 'name';
            $headerPlace = 'APPEND';
            
            if (isset($this->_headers[$headerName])) {
                $headerType  = $this->_headers[$headerName]['keyType'];
                $headerPlace = $this->_headers[$headerName]['placement'];
            }
            
            $view->headMeta($header['value'], $headerName, $headerType, array(), $headerPlace);
        }
    }

    /**
     * Excel post processing
     *
     * @return void
     */
    public function postExcelContext()
    {
        //TODO: need to implement
    }

    /**
     * PDF post processing
     *
     * @return void
     */
    public function postPdfContext()
    {
        //TODO: need to implement
    }

    /**
     * Initialize Negogiation context switching
     *
     * Checks for XHR requests; if detected, attempts to perform context switch.
     *
     * @param  string $format
     * @return void
     */
    public function initContext($format = null)
    {
        /*
         * set the accepted encodings/content types
         * TODO: check if its better to fins a better name for this
         */
        $this->_negotiateEncoding();
        $this->_negotiateType();
        
        /*
         * detect the encoding and set it to the registry and the view
         */
        $encoding = $this->_getEncodingMatch();
        mb_internal_encoding($encoding);
        mb_regex_encoding($encoding);

        $view = $this->getActionController()->view;

        $view->setEncoding($encoding);
        
        /*
         * detect the locale and set it to the registry
         */
        $sLocale = \Zend\Locale\Locale::findLocale();
        
        $oLocale = new \Zend\Locale\Locale($sLocale);
        \Zend\Registry::set('Zend_Locale', $oLocale);
        
        /*
         * detect the content type and search a matching context
         */
        $contexts = $this->getContexts();
        
        if (null === $format || !isset($contexts[$format])) {
            $contentType = $this->_getTypeMatch();
            
            /*
             * the format is not set or not defined
             * -> choose the format from the content type
             */
            foreach ($contexts as $context => $contextValues) {
                if (!isset($contextValues['headers']['Content-Type'])) {
                    continue;
                }
                
                $header = $contextValues['headers']['Content-Type'];
                
                if (is_string($header) && $contentType == $header) {
                    $format = $context;
                    break;
                }
            }
        }
        
        if (in_array($format, array('html', 'ajax'))) {
            $view->doctype()->setDoctype($this->_useHtmlFive ? \Zend\View\Helper\Doctype::HTML5 : ($this->_useXhtml ? \Zend\View\Helper\Doctype::XHTML1_TRANSITIONAL : \Zend\View\Helper\Doctype::HTML4_LOOSE));
        } elseif (in_array($format, array('xhtml', 'xajax', 'xhtmlmp', 'xajaxmp'))) {
            $view->doctype()->setDoctype($this->_useHtmlFive ? \Zend\View\Helper\Doctype::XHTML5 : \Zend\View\Helper\Doctype::XHTML1_STRICT);
        }
        
        // add the encoding to the content type
        $headers = $this->getHeaders($format);
        if (!empty($headers)) {
            $headerKeys = array_keys($headers);
            
            foreach ($headerKeys as $header) {
                $headers[$header] .= '; charset=' . $encoding;
            }
            
            $this->setHeaders($format, $headers);
        }
        
        // add the selected context to the request object
        $request = $this->getRequest();
        $request->setParam($this->getContextParam(), $format);
        
        // switch off auto disabling the layout
        $this->setAutoDisableLayout(false);

        parent::initContext($format);
        
        $suffix = $this->getSuffix($format);
        $view->negogationFormat = $format;

        $this->_getViewRenderer()->setViewSuffix($suffix);
        
        if (null !== $this->_layout) {
            // set the doctype depending on the content type or disable the layout
            $layout = $this->_layout;
            
            if (in_array($format, array('html', 'xhtml', 'xhtmlmp'))) {
                $layout->enableLayout();
            } else {
                $layout->disableLayout();
            }
        
            $layout->setViewSuffix($suffix);
        }
    }
    
    /**
     * sets the flag to tell that HTML5 should be used, if possible
     * 
     * @param boolean $usage
     *
     * @return ContentNegogation
     */
    public function setUseHtmlFive($usage = false)
    {
        $this->_useHtmlFive = (($usage) ? true : false);
        
        return $this;
    }
    
    /**
     * sets the flag to tell that HTML5 should be used, if possible
     * 
     * @param boolean $usage
     *
     * @return ContentNegogation
     */
    public function setUseXhtml($usage = false)
    {
        $this->_useXhtml = (($usage) ? true : false);
        
        return $this;
    }
    
    /**
     * sets a flag that the browser is prefered over the user definition
     * 
     * @param boolean $preferBrowser
     *
     * @return ContentNegogation
     */
    public function setPreferBrowser($preferBrowser = true)
    {
        if ($preferBrowser) {
            $this->_preferBrowser = true;
        } else {
            $this->_preferBrowser = false;
        }
        
        return $this;
    }
    
    /**
     * sets the default encoding
     * 
     * @param string $encoding
     *
     * @return ContentNegogation
     */
    public function setDefaultEncoding($encoding)
    {
        if (is_string($encoding)) {
            $this->_defaultEncoding = $encoding;
        }
        
        return $this;
    }
    
    /**
     * sets the default content type
     * 
     * @param string $contentType
     *
     * @return ContentNegogation
     */
    public function setDefaultContentType($contentType)
    {
        if (is_string($contentType)) {
            $this->_defaultType = $contentType;
        }
        
        return $this;
    }
    
    /**
     * sets the possible encodings to match against the possible ones
     * 
     * @param array|\Zend\Config\Config|string $encoding
     *
     * @return ContentNegogation
     */
    public function setMatchingEncoding($encoding)
    {
        if ($encoding instanceof \Zend\Config\Config) {
            $encoding = $encoding->toArray();
        }
        
        if (!is_array($encoding) && !is_string($encoding)) {
            return $this;
        }
        
        if (!is_array($encoding)) {
            $encoding = array($encoding);
        }
        
        $this->_wantedEncoding = $encoding;
        
        return $this;
    }
    
    /**
     * sets the default content type
     * 
     * @param array|\Zend\Config\Config|string $contentType
     *
     * @return ContentNegogation
     */
    public function setMatchingContentType($contentType)
    {
        if ($contentType instanceof \Zend\Config\Config) {
            $contentType = $contentType->toArray();
        }
        
        if (!is_array($contentType) && !is_string($contentType)) {
            return $this;
        }
        
        if (!is_array($contentType)) {
            $contentType = array($contentType);
        }
        
        $this->_wantedType = $contentType;
        
        return $this;
    }
    
    /**
     * sets a flag that the browser is prefered over the user definition
     * 
     * @param array|\Zend\Config\Config|string $type
     *
     * @return ContentNegogation
     */
    public function setPreferBowser($preferBrowser = false)
    {
        if ($preferBrowser) {
            $this->_preferBrowser = true;
        } else {
            $this->_preferBrowser = false;
        }
        
        return $this;
    }
    
    /**
     * Negotiate Encoding
     *
     * @return  void
     */
    private function _negotiateEncoding()
    {
        $request    = $this->getRequest();
        $server     = $request->getServer('HTTP_ACCEPT_CHARSET');
        
        if (null === $server) {
            $charsets = array_keys(\Zend\Locale\Locale::getHttpCharset());
        } else {
            $charsets = explode(',', $server);
        }
        
        $this->_acceptEncoding = $this->_break($charsets);
    }
    
    /**
     * Negotiate the Content Type
     *
     * @return  void
     */
    private function _negotiateType()
    {
        $request    = $this->getRequest();
        $accept     = $request->getServer('HTTP_ACCEPT');
        
        if (null === $accept) {
            return;
        }
        
        $this->_acceptType = $this->_break($accept);
    }
    
    /**
     * breaks a string into a array and deletes the weight of the parts
     *
     * @param string|array $input
     *
     * @return array
     */
    private function _break($input)
    {
        if (!is_string($input) && !is_array($input)) {
            return array();
        }
        
        if (is_array($input)) {
            $arrayInput = $input;
        } else {
            $arrayInput = explode(',', $input);
        }
        
        $arrayOutput = array();
        
        foreach ($arrayInput as $inputString) {
            if (!empty($inputString)) {
                $arrayOutput[] = preg_replace('/;.*/', '', $inputString);
            }
        }
        
        return $arrayOutput;
    }

    /**
     * Find Encoding match
     * 
     * @return  string
     */
    private function _getEncodingMatch()
    {
        $match = $this->_getMatch(
            (is_array($this->_wantedEncoding) ? $this->_wantedEncoding : array()), 
            $this->_acceptEncoding, 
            $this->_defaultEncoding
        );
        
        return strtolower($match);
    }

    /**
     * Find Content type match
     *
     * @return  string
     */
    private function _getTypeMatch()
    {
        $match = $this->_getMatch(
            (is_array($this->_wantedType) ? $this->_wantedType : array()), 
            $this->_acceptType,
            $this->_defaultType
        );
        
        return strtolower($match);
    }
    
    /**
     * Return first matched value from first and second parameter.
     * If there is no match found, then return third parameter.
     * 
     * @return  string
     * @param   array   $needle   the possible values set by the user
     * @param   array   $haystack the possible values taken from browser
     * @param   string  $default  the fefault value set by the user
     */
    private function _getMatch(array $needle, array $haystack, $default = '')
    {
        if (!$haystack) {
            return $default;
        }
        
        if (!$needle) {
            return current($haystack);
        }
        
        //var_dump($this->_preferBrowser, $haystack, $needle);
        if ($this->_preferBrowser) {
            $a = array_intersect($haystack, $needle);
        } else {
            $a = array_intersect($needle, $haystack);
        }
        
        if ($result = current($a)) {
            return $result;
        }
        
        return $default;
    }
    
    /**
     * an \Zend\Layout\Layout to manopulate the actual layout
     * 
     * @param \Zend\Layout\Layout $layout
     *
     * @return ContentNegogation
     */
    public function setLayout(\Zend\Layout\Layout $layout)
    {
        if ($layout instanceof \Zend\Layout\Layout) {
            $this->_layout = $layout;
        }
        
        return $this;
    }
    
    /**
     * an \Zend\Layout\Layout to manopulate the actual layout
     * 
     * @param \Zend\Layout\Layout $layout
     *
     * @return ContentNegogation
     */
    public function setLogger(\Zend\Log\Logger $logger)
    {
        if ($logger instanceof \Zend\Log\Logger) {
            $this->_logger = $logger;
        }
        
        return $this;
    }

    /**
     * Add multiple contexts
     *
     * @param  array $contexts
     * @return ContentNegogation Provides a fluent interface
     */
    public function addContexts(array $contexts)
    {
        foreach ($contexts as $context => $spec) {
            /*
             * ignore contexts which are set already
             */
            if (!$this->hasContext($context)) {
                $this->addContext($context, $spec);
            } elseif (null !== $this->_logger) {
                $this->_logger->warn('you tried to add an existing context');
            }
        }
        
        return $this;
    }

    /**
     * Strategy pattern: return object
     *
     * @return ContentNegogation Provides a fluent interface
     */
    public function direct($options = null)
    {
        if ($options instanceof \Zend\Config\Config) {
            $this->setConfig($options);
        } elseif (is_array($options)) {
            $this->setOptions($options);
        }
        
        return $this;
    }

    /**
     * Strategy pattern: return object
     *
     * @return ContentNegogation Provides a fluent interface
     */
    public function __invoke($options = null)
    {
        return $this->direct($options);
    }
}
