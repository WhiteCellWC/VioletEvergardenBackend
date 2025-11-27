<?php

namespace App\Models;

use App\Constant\Constant;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    #region Scopes
    public function scopeCondsInByColumns($query, $columnMap)
    {
        if (!empty($columnMap) && count($columnMap) > 0) {
            foreach ($columnMap as $column => $value) {
                if ($this->isValidColumn($column) && $value !== null) {
                    if (is_bool($value)) {
                        $value = $value ? 1 : 0;
                    }
                    $query->where($column, 'LIKE', "%$value%");
                }
            }
        }

        return $query;
    }

    public function scropeCondsNotInByColumns($query, $columnMap)
    {
        if (!empty($columnMap) && count($columnMap) > 0) {
            foreach ($columnMap as $column => $value) {
                if ($this->isValidColumn($column) && $value) {
                    $query->where($column, '!=', $value);
                }
            }
        }

        return $query;
    }

    public function scopeQueryOptions($query, $columnMap)
    {
        if (empty($columnMap) || count($columnMap) == 0) {
            return $columnMap;
        }

        foreach ($columnMap as $key => $value) {
            if (empty($value)) continue;

            switch ($key) {
                case 'orderBy':
                    $query->orderBy($value, $columnMap['orderType'] ?? Constant::descending);
                    break;

                case 'offset':
                    $query->offset($value);
                    break;

                case 'limit':
                    $query->limit($value);
                    break;
            }
        }

        return $query;
    }
    #endregion

    #region Private functions
    private function isValidColumn($column)
    {
        return in_array($column, $this->getFillable()) || in_array($column, $this->getGuarded());
    }
    #endregion

}
