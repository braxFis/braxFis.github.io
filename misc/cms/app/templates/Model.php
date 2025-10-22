<?php

namespace app\templates;
interface Model {

    public function create();
    public function edit($id);
    public function update($id, $data);
    public function delete($id);
}