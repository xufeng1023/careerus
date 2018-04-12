<?php

function create($model, array $data = [])
{
    return factory("App\\$model")->create($data);
}

function raw($model, array $data = [])
{
    return factory("App\\$model")->raw($data);
}
