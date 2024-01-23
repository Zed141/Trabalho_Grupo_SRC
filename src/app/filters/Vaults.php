<?php

namespace app\filters;

use app\orm\Vault;
use app\orm\VaultAccess;
use yii\base\Model;
use yii\data\ActiveDataProvider;

final class Vaults extends Model {

    private int $userId;
    /** @var string|null */
    public ?string $description = null;

    /**
     * @param int   $userId
     * @param array $config
     */
    public function __construct(int $userId, array $config = []) {
        $this->userId = $userId;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array {
        return [
            [['description'], 'string']
        ];
    }

    /**
     * @param array $params
     * @param int   $pageSize
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function search(array $params, int $pageSize = 75): ActiveDataProvider {
        $query = Vault::find()
            ->alias('v')
            ->innerJoin(VaultAccess::tableName() . ' AS va', 'v.id = va.vault_id')
            ->where(['va.user_id' => $this->userId])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $pageSize],
            'sort' => [
                'defaultOrder' => [
                    'description' => SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        //TODO: filter options
        //$query->andFilterWhere(['ilike', '', $this->]);
        return $dataProvider;
    }
}