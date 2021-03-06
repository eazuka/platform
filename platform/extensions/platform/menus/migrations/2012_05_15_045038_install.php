<?php
/**
 * Part of the Platform application.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Platform
 * @version    1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Platform\Menus\Menu;

class Menus_Install
{

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create menu table
		Schema::create('menus', function($table)
		{
			$table->increments('id')->unsigned();
			$table->string('extension')->nullable();
			$table->string('name');
			$table->string('slug')->unique();
			$table->string('uri');
			$table->boolean('user_editable');
			$table->integer('lft');
			$table->integer('rgt');
			$table->integer('menu_id');
			$table->boolean('status');
		});

		// Create menu items

		$admin = Menu::admin_menu();

		// Find the system menu
		$primary = Menu::find(function($query) use ($admin)
		{
			return $query->where('slug', '=', 'system');
		});

		if ($primary === null)
		{
			$primary = new Menu(array(
				'name'          => 'System',
				'slug'          => 'system',
				'uri'           => 'settings',
				'user_editable' => 0,
				'status'        => 1,
			));

			$primary->last_child_of($admin);
		}

		// "Menus" menu
		$menus = new Menu(array(
			'name'          => 'Menus',
			'extension'     => 'menus',
			'slug'          => 'menus',
			'uri'           => 'menus',
			'user_editable' => 0,
			'status'        => 1,
		));

		$menus->last_child_of($primary);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('menus');
	}

}
