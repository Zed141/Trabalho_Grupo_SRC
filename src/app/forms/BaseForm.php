<?php

namespace app\forms;

use yii\base\Model;

/**
 * @property object|null $model
 */
abstract class BaseForm extends Model {

    /** @var string|null */
    public ?string $_generic = null;

    /** @var string|null */
    public ?string $_warnings = null;

    /** @var object|null */
    protected ?object $model = null;

    /** @var int|string */
    private string|int $id;

    /**
     * @param string $id
     * @param array  $config
     */
    public function __construct($id = 'id', array $config = []) {
        $this->id = $id;
        parent::__construct($config);
    }

    /**
     * @return bool
     */
    public abstract function save(): bool;

    /**
     * @return int|string
     */
    public function getId(): int|string {
        if (!$this->model) {
            return 0;
        }

        $local = $this->id;
        if (!$this->isNewRecord()) {
            return $this->model->$local;
        }

        return is_int($this->model->$local) ? 0 : '';
    }

    /**
     * @return bool
     */
    public function isNewRecord(): bool {
        $local = $this->id;
        if (!$this->model) {
            return true;
        }

        if ($this->model->$local === 0 || $this->model->$local === '0') {
            return $this->model->getIsNewRecord();
        }

        return !$this->model->$local;
    }

    /**
     * @return object
     */
    public function getModel(): object {
        return $this->model;
    }

    /**
     * @return string|null
     */
    public function getGenericError(): ?string {
        if ($this->hasGenericError()) {
            return implode('<br />', $this->getErrorSummary('_generic'));
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasGenericError(): bool {
        return $this->hasErrors('_generic');
    }

    /**
     * @return string|null
     */
    public function getWarnings(): ?string {
        if ($this->hasWarnings()) {
            return implode('<br />', $this->getErrorSummary('_warnings'));
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasWarnings(): bool {
        return $this->hasErrors('_warnings');
    }

    /**
     * @param string $error
     */
    public function addGenericError(string $error = ''): void {
        parent::addError('_generic', $error);
    }

    /**
     * @param string $warning
     */
    public function addWarningError(string $warning = ''): void {
        parent::addError('_warnings', $warning);
    }
}
