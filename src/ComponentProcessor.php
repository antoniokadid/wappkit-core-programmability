<?php

namespace AntonioKadid\WAPPKitCore\Programmability;

use AntonioKadid\WAPPKitCore\IO\Exceptions\IOException;
use AntonioKadid\WAPPKitCore\Programmability\Exceptions\ProgrammabilityException;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * Class ComponentProcessor
 *
 * @package AntonioKadid\WAPPKitCore\Programmability
 */
class ComponentProcessor
{
    const COMPONENT_DEFINITION = '/#\{\{\s*(?<name>\w+)(?:\s+(?<parameters>[^}}]+))?\s*\}\}/i';

    /**
     * Contains the contents of dynamic components
     *
     * @var Component[]
     */
    private $_cache;

    /**
     * A callable that will convert a component name into its contents.
     *
     * @var callable
     */
    private $_componentEvaluator;

    /**
     * ComponentProcessor constructor.
     *
     * @param callable $componentEvaluator A callable that will convert a component name into its contents.
     */
    public function __construct(callable $componentEvaluator)
    {
        $this->_cache = [];
        $this->_componentEvaluator = $componentEvaluator;
    }

    /**
     * @param string                $content
     *
     * @param ExecutionContext|null $context
     *
     * @return string
     */
    public function processContent(string $content, ExecutionContext $context = NULL): ?string
    {
        return preg_replace_callback(
            self::COMPONENT_DEFINITION,
            function (array $matches) use ($context) {
                $name = $matches['name'];

                $parameters = [];
                if (array_key_exists('parameters', $matches))
                    parse_str($matches['parameters'], $parameters);

                if (array_key_exists($name, $this->_cache)) {
                    $component = $this->_cache[$name];
                    $component->setParameters($parameters);
                    $component->setContext($context);

                    return $this->processComponent($component);
                }

                $content = call_user_func_array($this->_componentEvaluator, [$name]);
                if ($content === FALSE || $content == NULL)
                    return $matches[0];

                $component = new Component($name, $content, $parameters, $context);
                $this->_cache[$name] = $component;

                return $this->processComponent($component);
            }, $content);
    }

    /**
     * @param Component $component
     *
     * @return string
     *
     * @throws IOException
     * @throws ProgrammabilityException
     */
    public function processComponent(Component $component): string
    {
        if (!$this->classDefinedForComponent($component))
            CodeEvaluator::evaluate($component->getContent());

        $instance = $this->classInstanceForComponent($component);
        if ($instance == NULL)
            return '';

        try {
            $content = $instance->generate();
        } catch (Exception $exception) {
            throw new ProgrammabilityException(sprintf('Unable to generate code of %s', $component->getName()), $component->getParameters(), $component->getContext(), $exception);
        }

        $context = new ExecutionContext($component->getParameters(), $component->getContext());

        return $this->processContent($content, $context);
    }

    /**
     * @param Component $component
     *
     * @return bool
     */
    private function classDefinedForComponent(Component $component): bool
    {
        return class_exists($component->getClassName());
    }

    /**
     * @param Component $component
     *
     * @return IComponentImplementation|null
     */
    private function classInstanceForComponent(Component $component): ?IComponentImplementation
    {
        if (!$this->classDefinedForComponent($component))
            return NULL;

        try {
            $class = new ReflectionClass($component->getClassName());
            if (!$class->implementsInterface(IComponentImplementation::class))
                return NULL;

            /** @var IComponentImplementation $instance */
            $instance = $class->newInstance();
            $instance->setData($component->getParameters(), $component->getContext());

            return $instance;
        } catch (ReflectionException $e) {
            return NULL;
        }
    }
}