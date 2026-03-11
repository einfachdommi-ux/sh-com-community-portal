<?php

class TeamController extends Controller
{
    public function index()
    {
        $team = new TeamMember();
        $members = $team->all();

        return View::render("app/Views/admin/team/index", [
            "members" => $members
        ]);
    }

    public function edit($id)
    {
        $team = new TeamMember();
        $member = $team->find($id);

        return View::render("app/Views/admin/team/edit", [
            "member" => $member
        ]);
    }

    public function update($id)
    {
        $team = new TeamMember();

        $team->update($id, $_POST);

        header("Location: app/Views/admin/team");
    }

    public function create()
    {
        return View::render("app/Views/admin/team/create");
    }

    public function store()
    {
        $team = new TeamMember();
        $team->create($_POST);

        header("Location: app/Views//admin/team");
    }

    public function delete($id)
    {
        $team = new TeamMember();
        $team->delete($id);

        header("Location: app/Views/admin/team");
    }
}

?>