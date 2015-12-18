<?php

namespace Tajrish\Helpers;


class RequestHelper
{
    public $request;
    public $model;

    /**
     *
     */
    const ORDER_ASC = 'ASC';
    /**
     *
     */
    const ORDER_DESC = 'DESC';

    /**
     * @var null
     */
    protected $orderBy = null;

    /**
     * @var string
     */
    protected $orderType = self::ORDER_ASC;

    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var
     */
    protected $filter;

    /**
     * @var array
     */
    protected $fields = ['*'];

    /**
     * @var array
     */
    protected $include = [];

    /**
     * @var string
     */
    protected $trashedMethod = 'withoutTrashed';

    /**
     * @var string
     */
    protected $deleteMethod = 'soft';

    /**
     * @var null
     */
    protected $searchValue = null;

    /**
     * @var array
     */
    protected $credentials = [];

    /**
     * @return array
     * @throws \Exception
     */
    public function getTableColumns()
    {
        return $this->model->columns;
    }

    /**
     * @return mixed|null|string
     */
    public function getOrderBy()
    {

        $sort = $this->request->get('sort');

        if (is_null($sort)) {
            return $this->orderBy;
        }

        $orderBy   = $sort;
        $dashPlace = strpos($sort, '-');
        if ($dashPlace !== false and $dashPlace == 0) {
            $orderBy = substr($sort, 1);
        }

        if (!in_array($orderBy, $this->getTableColumns())) {
            $orderBy = $this->orderBy;
        }

        return $orderBy;
    }

    /**
     * @return string
     */
    public function getOrderType()
    {
        $sort = $this->request->get('sort');

        if (is_null($sort)) {
            return $this->orderType;
        }

        $orderType = $this->orderType;
        $dashPlace = strpos($sort, '-');
        if ($dashPlace !== false and $dashPlace == 0) {
            $orderType = self::ORDER_DESC;
        }

        return $orderType;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        $per_page = $this->request->get('per_page');

        if (!is_numeric($per_page)) {
            return $this->limit;
        }

        return (int)$per_page;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        $fields = $this->request->get('fields');

        $fieldsArray = explode(',', $fields);

        if (empty($fieldsArray) or is_null($fields)) {
            return $this->fields;
        }

        $columns = [];
        foreach ($fieldsArray as $column) {
            if (!in_array($column, $this->getTableColumns())) {
                continue;
            }

            $columns[] = $column;
        }

        return empty($columns) ? $this->fields : $columns;
    }

    /**
     * @return array
     */
    public function getQueryStringWithoutPage()
    {
        return array_except($this->request->query(), 'page');
    }

    /**
     * @return string
     */
    public function getTrashedMethod()
    {
        $trashed = $this->request->get('trashed', null);

        if (is_null($trashed) or !in_array(strtolower($trashed), array ('with', 'only', 'without'))) {
            return $this->trashedMethod;
        }

        return $trashed . 'Trashed';
    }

    /**
     * @return string
     */
    public function getDeleteMethod()
    {
        $delete = $this->request->get('delete', null);

        if (is_null($delete) or !in_array(strtolower($delete), array ('soft', 'force'))) {
            return $this->deleteMethod;
        }

        return $delete;
    }

    /**
     * @return array
     */
    public function getInputs()
    {
        $data = $this->request->only($this->getTableColumns());

        //if (array_key_exists('user_id', $data)) {
        //    $user = Acl::getCertainUser();
        //    if ($user->getId() !== 'guest') {
        //        $data['user_id'] = $user->getId();
        //    }
        //}

        $data = array_filter($data, function($item){
            return $item !== null;
        });

        return $data;
    }

    /**
     * @return mixed|null
     */
    public function getSearchValue()
    {
        $queryString = $this->request->get('q');

        if (empty($queryString) or is_null($queryString)) {
            return $this->searchValue;
        }

        return $queryString;
    }

    /**
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}