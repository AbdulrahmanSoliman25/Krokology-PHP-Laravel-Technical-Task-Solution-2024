<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    /**
     * Retrieve records with optional filtering, ordering, pagination, and search.
     *
     * @param array|null $columns
     * @param array $with
     * @param array $search
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function retrieve($columns = ['*'], $search, $options);

    /**
     * Create a new record.
     *
     * @param array $data Data to create the record.
     * @return mixed The created record.
     */
    public function create(array $data);

    /**
     * Update an existing record.
     *
     * @param array $data Data to update the record.
     * @param mixed $id The ID of the record to update.
     * @param string $attribute The attribute to use for identifying the record.
     * @return mixed The updated record.
     */
    public function update(array $data, $id, $attribute = "id");

    /**
     * Delete a record by its ID.
     *
     * @param mixed $id The ID of the record to delete.
     * @return mixed The result of the deletion operation.
     */
    public function delete($id);

    /**
     * Find a record by its ID.
     *
     * @param mixed $id The ID of the record to find.
     * @param array $columns Columns to retrieve.
     * @return mixed The found record.
     */
    public function find($id, $columns = ['*']);

    /**
     * Find a record by a specific attribute.
     *
     * @param string $attribute The attribute to search for.
     * @param mixed $value The value of the attribute.
     * @param array $columns Columns to retrieve.
     * @return mixed The found record.
     */
    public function findBy($attribute, $value, $columns = ['*']);


    /**
     * Get the model class name.
     *
     * @return string The fully qualified class name of the model.
     */
    public function model();

    /**
     * Create a new instance of the model.
     *
     * @return Model The new model instance.
     */
    public function makeModel(): Model;
}
