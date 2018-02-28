<?php

namespace ST\WP_Activity_Summary;

abstract class Table {

    abstract public function get_records();

    abstract public function get_columns();

    abstract public function column_default($record, $column);

    public function __toString() {
        return $this->html();
    }
    
    public function html() {
        $records = $this->get_records();
        ?>
        <table>
            <thead>
                <tr>
                    <?php foreach ($this->get_columns() as $column) : ?>
                        <th><?= $column; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record) : ?>
                    <tr>
                        <?php foreach ($this->get_columns() as $key => $column) : ?>
                            <?php if ( method_exists( $this, 'column_' . $key ) ) : ?>
                                <td><?= call_user_func( array( $this, 'column_' . $key ), $record ); ?></td>
                            <?php else : ?>
                                <td><?= $this->column_default($record, $key); ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
}