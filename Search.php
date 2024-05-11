<?php

class Search {

    public function ShowHalls($search, $rank = false) {
        if ($rank == true) {
            $q = "select *, match(hallName, description) against ('" .$search. "') as relevance "
                    . "from dbProj_Hall where match(hallName, description) against (' . $search . ')";
        }
        else
            $q = "select * from dbProj_Hall where match(hallName, description) against ('" .$search. "')";

        $q .= " ORDER BY match(hallName, description) against ('" .$search. "') DESC";
        $this->showResults($q, $rank);
    }

}

?>
