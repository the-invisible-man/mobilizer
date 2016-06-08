// 
// JSONHttpRequest 0.3.0
//
// Copyright 2011 Torben Schulz <http://pixelsvsbytes.com/>
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with _self program. If not, see <http://www.gnu.org/licenses/>.
// 
///////////////////////////////////////////////////////////////////////

function JSONHttpRequest() {
	var _xmlHttpRequest = new XMLHttpRequest();
	var _responseJSON = null;
	var _userContentType = false;
	// INFO Defining 'this' as '_self' improves compression, since keywords won't be shortened,
	//      but variables will, and it delivers us from the need to reset the scope of the anonymous
	//      function in the for-loop via call() or apply().
	var _self = this;

	var property = {
		get: function() {
			try {
				_responseJSON = _xmlHttpRequest.responseText ? (!_responseJSON ? JSON.parse(_xmlHttpRequest.responseText) : _responseJSON) : null;
			}
			catch (e) {
				if (_self.strictJSON)
					throw e;
			}
			return _responseJSON;
		},
		enumerable: true,
		configurable: true
	}
	
	_self.strictJSON = true;
	Object.defineProperty(_self, 'responseJSON', property);
	
	_self.sendJSON = function(data) {
    	try {
    		data = JSON.stringify(data);
    		_responseJSON = null;
    		if (!_userContentType)
    			_xmlHttpRequest.setRequestHeader('Content-Type', 'application/json;charset=encoding');    		
    		_userContentType = false;
		}
		catch (e) {
			if (_self.strictJSON)
				throw e;
		}
		_xmlHttpRequest.send(data);
    }
	
	// INFO proxy setup
	
	function proxy(name) {
		try {
			if ((typeof _xmlHttpRequest[name]) == 'function') {
				_self[name] = function() {
					if (name == 'setRequestHeader')
						_userContentType = arguments[0].toLowerCase() == 'content-type';
					return _xmlHttpRequest[name].apply(_xmlHttpRequest, Array.prototype.slice.apply(arguments));
				};
			}
			else {
				property.get = function() { return _xmlHttpRequest[name]; }
				property.set = function(value) { _xmlHttpRequest[name] = value; }
				Object.defineProperty(_self, name, property);	
			}
		}
		catch (e) {
			// NOTE Swallow any exceptions, which may rise here.
		}
	}
	
	// FIX onreadystatechange is not enumerable [Opera]
	proxy('onreadystatechange');
	
	for (n in _xmlHttpRequest)
		proxy(n);
}