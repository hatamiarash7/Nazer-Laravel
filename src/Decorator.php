<?php
/**
 * Created by PhpStorm.
 * User: hatamiarash7
 * Date: 2018-12-17
 * Time: 21:33
 */

namespace Hatamiarash7\Nazer;

use Closure;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;

class Decorator implements ExceptionHandler
{
	/**
	 * @var        Illuminate\Contracts\Debug\ExceptionHandler $handler Laravel default exception handler.
	 */
	protected $handler;
	/**
	 * @var        Cerbero\ExceptionHandler\Repository $handlers Custom exception handlers repository.
	 */
	protected $handlers;

	/**
	 * Set the dependencies.
	 *
	 * @author    Andrea Marco Sartori
	 * @param    Illuminate\Contracts\Debug\ExceptionHandler $handler
	 * @param    Cerbero\ExceptionHandler\Repository $handlers
	 * @return    void
	 */
	public function __construct(ExceptionHandler $handler, Repository $handlers)
	{
		$this->handler = $handler;
		$this->handlers = $handlers;
	}

	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		foreach ($this->handlers->getReportersFor($e) as $reporter) {
			if ($report = $reporter($e)) {
				return $report;
			}
		}
		$this->handler->report($e);
	}

	/**
	 * Register a custom handler to report exceptions.
	 *
	 * @author    Andrea Marco Sartori
	 * @param    \Closure $reporter
	 * @return    integer
	 */
	public function reporter(Closure $reporter)
	{
		return $this->handlers->addReporter($reporter);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception $e
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function render($request, Exception $e)
	{
		foreach ($this->handlers->getRenderersFor($e) as $renderer) {
			if ($render = $renderer($e, $request)) {
				return $render;
			}
		}
		return $this->handler->render($request, $e);
	}

	/**
	 * Register a custom handler to render exceptions.
	 *
	 * @author    Andrea Marco Sartori
	 * @param    \Closure $renderer
	 * @return    integer
	 */
	public function renderer(Closure $renderer)
	{
		return $this->handlers->addRenderer($renderer);
	}

	/**
	 * Render an exception to the console.
	 *
	 * @param  \Symfony\Component\Console\Output\OutputInterface $output
	 * @param  \Exception $e
	 * @return void
	 */
	public function renderForConsole($output, Exception $e)
	{
		foreach ($this->handlers->getConsoleRenderersFor($e) as $renderer) {
			if ($render = $renderer($e, $output)) {
				return $render;
			}
		}
		$this->handler->renderForConsole($output, $e);
	}

	/**
	 * Register a custom handler to render exceptions to the console.
	 *
	 * @author    Andrea Marco Sartori
	 * @param    \Closure $renderer
	 * @return    integer
	 */
	public function consoleRenderer(Closure $renderer)
	{
		return $this->handlers->addConsoleRenderer($renderer);
	}
}