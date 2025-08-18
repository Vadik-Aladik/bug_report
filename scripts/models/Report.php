<?php 

    class Report extends QueryBuilder
    {
        public function reports_user()
        {
            return $this->select('reports', 'reports.id, reports.title, reports.description, reports.priority, reports.status, reports.created_at, users.login')->innerJoin('users', 'user_id', 'id');   
        }
    }

?>