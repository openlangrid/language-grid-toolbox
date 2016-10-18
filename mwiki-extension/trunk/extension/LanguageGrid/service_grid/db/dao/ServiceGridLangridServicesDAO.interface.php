<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
interface ServiceGridLangridServicesDAO{

	public function queryAll();
	/*
	 * @param $serviceId
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryGetByServiceId($serviceId, $serviceType = '');

	/*
	 * エンドポイントを指定して検索
	 * @param $endpoint
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryGetByEndPoint($endpoint, $serviceType = '');

	/*
	 * サービスタイプを指定して検索
	 * @param $serviceType
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryFindByServiceTypes($serviceTypes);
}
?>