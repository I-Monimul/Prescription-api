<?php
namespace Models;

class Base extends \DB\Cortex
{
  protected $app,
    $fieldConf = [],
    $fluid = false,
    $db = 'DB',
    $ttl = 0,

    $autoModifiedCreated = true,
    $softDelete = true;

  public function __construct()
  {
    $this->app = \Base::instance();

    if (K_ENV == K_ENV_PRODUCTION) {
      $this->ttl = 86400;
    }

    $this->fixRelations();

    parent::__construct();

    $this->beforeinsert(function ($self) {
      return self::modifiedCreated($self);
    });

    $this->beforeupdate(function ($self) {
      return self::modifiedCreated($self);
    });

    $this->beforeerase(function ($self) {
      return self::checkSoftDelete($self);
    });
  }

  protected function fixRelations()
  {
    foreach ($this->fieldConf as $k => &$v) {
      foreach ($v as $k1 => &$v1) {
        if ($k1 == 'belongs-to-one' || $k1 == 'has-many' || $k1 == 'has-one') {
          if (is_array($v1)) {
            $v1[0] = $this->getNamespacedClass($v1[0]);
          } else {
            $v1 = $this->getNamespacedClass($v1);
          }
        }
      }
    }

  }

  protected function getNamespacedClass($fqcn)
  {
    $curClass = explode("\\", get_class($this));
    array_pop($curClass);
    $curNamespace = implode("\\", $curClass);

    $givenClass = explode("\\", $fqcn);
    $class = array_pop($givenClass);

    if ($curNamespace !== "Models\\Base" && class_exists($curNamespace . "\\" . $class)) {
            //inject current model's namespace if it isn't Base
            //the next condition will catch it and inject an extended model
      $class = $curNamespace . "\\" . $class;
    } elseif (class_exists("Models\\" . $class)) {
            //inject extended models namespace
      $class = "Models\\" . $class;
    } elseif (class_exists("Models\\Base\\" . $class)) {
            //inject base models namespace
      $class = "Models\\Base\\" . $class;
    }

    return $class;
  }

  /**
   * automatically patches the modified and created dates
   * on models that have those fields
   */
  public static function modifiedCreated($instance)
  {
    if ($instance->autoModifiedCreated) {

      if (isset($instance->fieldConf['created'])) {
        if (empty($instance->created)) {
          $instance->touch('created');
        }
      }
      if (isset($instance->fieldConf['modified'])) {
        $instance->touch('modified');
      }
    }

    return true;
  }

  /**
   * Automatically applies soft-delete if model supports it
   */
  public static function checkSoftDelete($instance)
  {
    if ($instance->softDelete && isset($instance->fieldConf['deleted'])) {
      $instance->deleted = 1;
      $instance->save();
      return false;
    }

    return true;
  }

  /**
   * Parser for filters
   * converts filters to a PDO friendly stringy thingy
   */
  public static function filteredQuery($filters, $existingQuery = '', $existingBindings = [], $tablename = NULL)
  {
    $map = array(
      'eq' => '=',
      'xeq' => '<>',
      'gt' => '>',
      'gte' => '>=',
      'lt' => '<',
      'lte' => '<='
    );
    $bind = [];
    $query = '';
    $i = 0;

    if (is_array($filters)) {
      foreach ($filters as $filter) {

        if (isset($filter['field']) && isset($filter['value'])) {
                    //if field does not contain a . and tablename is given, prepend it
          if ($tablename && strpos($filter['field'], '.') == false) {
            $field = '`' . $tablename . '`.`' . $filter['field'] . '`';
          } else {
            $field = '`' . $filter['field'] . '`';
          }

                    //make the fieldname not clash with already prepared statement's named params
          $namedParam = uniqid(':fq_' . $filter['field'] . '_');
          $condition = $filter['condition'];
          $translation = $filter['translation'];
          $value = $filter['value'];

          $queryPart = '';

          if (isset($map[$condition])) {
            $queryPart = $field . " " . $map[$condition] . " " . $namedParam;
            $bind[$namedParam] = $value;
          } else {
            switch ($condition) {
              case 'null':
                if ($filter['value'] == 'true') {
                  $queryPart = $field . " IS NULL";
                } else {
                  $queryPart = $field . " IS NOT NULL";
                }
                break;
              case 'lk':
                $queryPart = $field . " LIKE " . $namedParam;
                $bind[$namedParam] = '%' . $value . '%';
                break;
              case 'bw':
                $queryPart = $field . " LIKE " . $namedParam;
                $bind[$namedParam] = $value . '%';
                break;
              case 'ew':
                $queryPart = $field . " LIKE " . $namedParam;
                $bind[$namedParam] = '%' . $value;
                break;
              case 'xlk':
                $queryPart = $field . " NOT LIKE " . $namedParam;
                $bind[$namedParam] = '%' . $value . '%';
                break;
              case 'xbw':
                $queryPart = $field . " NOT LIKE " . $namedParam;
                $bind[$namedParam] = $value . '%';
                break;
              case 'xew':
                $queryPart = $field . " NOT LIKE " . $namedParam;
                $bind[$namedParam] = '%' . $value;
                break;
            }
          }

                    //we dont care about the join type of the first field, it will be ANDed in brackets anyway
          $joinType = ($i == 0 ? '' : ' ' . $filter['jointype'] . ' ');
          $query .= !empty($queryPart) ? $joinType . $queryPart : '';

          $i++;
        }

      }
    }

    if (!empty($existingQuery)) {
      $query = $existingQuery . (!empty($query) ? " AND (" . $query . ")" : "");
    } else {
      $query = (!empty($query) ? " " . $query . "" : "");
    }


    if (empty($query)) {
      return null;
    }

    $qobj = array_merge([0 => $query], (array)$existingBindings, $bind);

    return $qobj;
  }

  /**
   * Validates given properties are not empty
   */
  public static function checkMandatoryFields(&$model, $fields = [])
  {
    if (!is_array($fields) || empty($model)) {
      return false;
    }
    foreach ($fields as $field) {
      if (empty($model->{$field})) {
        return false;
      }
    }
    return true;
  }

}
