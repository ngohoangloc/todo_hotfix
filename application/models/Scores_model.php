<?php

class Scores_model extends CI_Model
{
    const GROUP_ID = 5;
    const PROJECT_ID = 6;
    const TASK_ID = 8;
    const SUB_TASK = 28;
    const FOLDER_ID = 7;
    const BOARD_ID = 27;
    const DEPARTMENT_ID = 30;
    const TABLE_ID = 31;
    const TIMETALBE_ID = 32;
    const TABLEITEM_ID = 35;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all($filters = [])
    {
        $this->db->select('scores.* ,users.id AS user_id, users.firstname, users.lastname, items.title AS department_name');
        $this->db->from('scores');
        $this->db->join('users', 'users.id = scores.user_id');
        $this->db->join('items', 'users.department_id = items.id', 'left');

        if (!empty($filters['year'])) {
            $this->db->where('YEAR(scores.created_at)', $filters['year']);
        }

        if (!empty($filters['month'])) {
            if (empty($filters['year'])) {
                $this->db->where('YEAR(scores.created_at)', date('Y'));
            }
            $this->db->where('MONTH(scores.created_at)', $filters['month']);
        }

        if (!empty($filters['department'])) {
            $this->db->where('users.department_id', $filters['department']);
        }

        if (!empty($filters['search'])) {
            $this->db->like("CONCAT(users.firstname, ' ', users.lastname)", $filters['search']);
        }

        $this->db->order_by('id', 'DESC');

        // if (!empty($filters['limit']) && !empty($filters['offset'])) {
        //     $this->db->limit($filters['limit'], $filters['offset']);
        // }

        $query = $this->db->get();

        return $query->result_object();
    }

    public function count_get_all($filters = [])
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('scores');
        $this->db->join('users', 'users.id = scores.user_id');
        $this->db->join('items', 'users.department_id = items.id', 'left');

        if (!empty($filters['year'])) {
            $this->db->where('YEAR(scores.created_at)', $filters['year']);
        }

        if (!empty($filters['month'])) {
            if (empty($filters['year'])) {
                $this->db->where('YEAR(scores.created_at)', date('Y'));
            }
            $this->db->where('MONTH(scores.created_at)', $filters['month']);
        }

        if (!empty($filters['department'])) {
            $this->db->where('users.department_id', $filters['department']);
        }

        if (!empty($filters['search'])) {
            $this->db->like("CONCAT(users.firstname, ' ', users.lastname)", $filters['search']);
        }

        $query = $this->db->get();
        return $query->row()->total;
    }

    public function count_employees($filters = [])
    {
        $this->db->from('users');
        $this->db->where('status', true);
        $this->db->where('department_id IS NOT NULL', null, false);

        return $this->db->count_all_results();
    }

    public function count_departments($filters = [])
    {

        $this->db->from('items');
        $this->db->where('deleted_at', null);
        $this->db->where('parent_id', $this->config->item('id_bang_phong_ban'));
        $this->db->where('type_id', self::TABLEITEM_ID);

        return $this->db->count_all_results();
    }

    public function top_department($filters = [])
    {
        $query = $this->db
            ->select('users.department_id, AVG(scores.score) as avg_score, items.title as department_name')
            ->from('scores')
            ->join('users', 'scores.user_id = users.id', 'left')
            ->join('items', 'items.id = users.department_id', 'left')
            ->group_by('users.department_id')
            ->order_by('avg_score', 'DESC')
            ->limit(1)
            ->get();

        return $query->row();
    }

    public function avg_score($filters = [])
    {
        $this->db->select('AVG(score) as avg_score');
        $this->db->from('scores');
        $this->db->join('users', 'scores.user_id = users.id', 'left');
        $this->db->join('items', 'items.id = users.department_id', 'left');
        $this->db->where('deleted_at', null);

        if (!empty($filters['year'])) {
            $this->db->where('YEAR(scores.created_at)', $filters['year']);
        }

        if (!empty($filters['month'])) {
            if (empty($filters['year'])) {
                $this->db->where('YEAR(scores.created_at)', date('Y'));
            }
            $this->db->where('MONTH(scores.created_at)', $filters['month']);
        }

        if (!empty($filters['department'])) {
            $this->db->where('users.department_id', $filters['department']);
        }


        return $this->db->get()->row_object();
    }

    public function get_by_id($id)
    {
        $score = $this->db->get_where('scores', ['id' => $id])->row_object();

        return $score;
    }

    public function get_scores_in_current_year_by_user($user_id)
    {
        $scores = $this->db->get_where('scores', ['user_id' => $user_id])->result_object();

        return $scores;
    }

    public function get_by_user_and_item($user_id, $item_id)
    {
        $score = $this->db->get_where('scores', ['items_id' => $item_id, 'user_id' => $user_id])->row_object();

        return $score;
    }

    public function pie_chart_data($filters = [])
    {
        $this->db->select('COUNT(*) * 100 / (SELECT COUNT(*) FROM scores) as percentage, COUNT(*) as count, score');
        $this->db->from('scores');
        $this->db->join('users', 'users.id = scores.user_id');
        $this->db->join('items', 'users.department_id = items.id', 'left');
        if (!empty($filters['year'])) {

            if (!is_array($filters['year'])) {
                $filters['year'] = explode(',', $filters['year']);
            }

            $this->db->where_in('YEAR(scores.created_at)', $filters['year']);
        }

        if (!empty($filters['department'])) {

            if (!is_array($filters['department'])) {

                $filters['department'] = explode(',', $filters['department']);
            }

            $this->db->where_in('users.department_id', $filters['department']);
        }
        $this->db->group_by('score');
        $this->db->order_by('score', 'DESC');

        $query = $this->db->get();

        return $query->result_object();
    }

    public function bar_chart_data($filters = [])
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $lastMonth = date('m', strtotime('last month'));
        $lastYear = date('Y', strtotime('last month'));
        $previousYear = $currentYear - 1;

        $this->db->select('AVG(score) as average_score, MONTH(scores.created_at) as month, YEAR(scores.created_at) as year');
        $this->db->from('scores');

        $this->db->join('users', 'users.id = scores.user_id');
        $this->db->join('items', 'users.department_id = items.id', 'left');

        $this->db->where("(MONTH(scores.created_at) = $currentMonth AND YEAR(scores.created_at) = $currentYear)
        OR (MONTH(scores.created_at) = $lastMonth AND YEAR(scores.created_at) = $lastYear)
        OR (MONTH(scores.created_at) = $currentMonth AND YEAR(scores.created_at) = $previousYear)");

        if (!empty($filters['department'])) {
            if (!is_array($filters['department'])) {
                $filters['department'] = explode(',', $filters['department']);
            }
            $this->db->where_in('users.department_id', $filters['department']);
        }

        $this->db->group_by('MONTH(scores.created_at)');
        $this->db->group_by('YEAR(scores.created_at)');
        $this->db->order_by('YEAR(scores.created_at)');

        $query = $this->db->get();

        return $query->result_object();
    }

    public function create($_score)
    {
        $score = $this->get_by_user_and_item($_score['user_id'], $_score['item_id']);

        if (!empty($score)) {
            $result = $this->update($score->id, $_score);
        } else {
            $score = [
                'score' => !empty($_score['score']) ? $_score['score'] : 10,
                'desc' => $_score['desc'],
                'user_id' => $_score['user_id'],
                'items_id' => $_score['item_id'],
            ];

            $result = $this->db->insert('scores', $score);
        }

        return $result;
    }

    public function update($id, $_score)
    {
        $score = $this->get_by_id($id);

        if ($score) {
            $result = $this->db->update('scores', $_score, ['id' => $id]);
            return $result;
        } else {
            return false;
        }
    }

    public function avg_scores_by_year($user_id, $year)
    {
        $scores = $this->db->get_where('scores', ['user_id' => $user_id, 'YEAR(`created_at`)' => $year])->result_object();

        $total_score = 0;

        foreach ($scores as $score) {
            $total_score += $score->score;
        }

        return (int)($total_score / count($scores));
    }

    public function avg_scores_group_by_user($filters)
    {
        $this->db->select('users.id AS user_id, users.firstname, users.lastname, items.title AS department_name, AVG(scores.score) AS average_score');

        $this->db->from('scores');
        $this->db->join('users', 'users.id = scores.user_id');
        $this->db->join('items', 'users.department_id = items.id', 'left');

        if (!empty($filters['year'])) {
            $this->db->where('YEAR(scores.created_at)', $filters['year']);
        }

        if (!empty($filters['month'])) {
            if (empty($filters['year'])) {
                $this->db->where('YEAR(scores.created_at)', date('Y'));
            }
            $this->db->where('MONTH(scores.created_at)', $filters['month']);
        }

        if (!empty($filters['department'])) {
            $this->db->where('users.department_id', $filters['department']);
        }

        if (!empty($filters['search'])) {
            $this->db->like("CONCAT(users.firstname, ' ', users.lastname)", $filters['search']);
        }

        $this->db->group_by('scores.user_id');

        $this->db->order_by('users.department_id', 'ASC');

        if (!empty($filters['limit']) && !empty($filters['offset'])) {
            $this->db->limit($filters['limit'], $filters['offset']);
        }

        $query = $this->db->get();

        return $query->result_object();
    }

    public function count_total_scores($filters)
    {
        $this->db->from('scores');

        $this->db->join('users', 'users.id = scores.user_id');
        $this->db->join('items', 'users.department_id = items.id', 'left');

        if (!empty($filters['year'])) {
            $this->db->where('YEAR(scores.created_at)', $filters['year']);
        }
        if (!empty($filters['month'])) {
            $this->db->where('MONTH(scores.created_at)', $filters['month']);
        }
        if (!empty($filters['department'])) {
            $this->db->where('users.department_id', $filters['department']);
        }
        if (!empty($filters['search'])) {
            $this->db->like("CONCAT(users.firstname, ' ', users.lastname)", $filters['search']);
        }

        $total_rows = $this->db->count_all_results();

        return $total_rows;
    }
}
