<?php
namespace App\Traits;

/**
 * Filter Trait for common types
 */
trait FilterTrait
{
    public function commonFilter($query_builder){
        $query_builder->when(request("uuid"), function($query){
            return $query->whereUuid(request("uuid"));
        });
        $query_builder->when(request("employee_id"), function($query){
            return $query->where("employee_id", request("employee_id"));
        });
        $query_builder->when(request("department"), function($query){
            return $query->where("employee_department_id", request("department"));
        });
        $query_builder->when(request("current_status"), function($query){
            return $query->where("current_status", request("current_status"));
        });
        $query_builder->when(request("from_date"), function($query){
            return $query->whereDate("from_date", ">=", request("from_date"));
        });
        $query_builder->when(request("to_date"), function($query){
            return $query->whereDate("to_date", "<=", request("to_date"));
        });
        $query_builder = $query_builder->whereHas("employee", function($query){
            return employee_global_filter($query);
        });
        return $query_builder;
    }
}
