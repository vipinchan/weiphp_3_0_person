! function(t, e) {
	function s() {
		if (t.navigator.userAgent ? t.navigator.userAgent.toLowerCase() : "", this.hasDeviceMotion = "ondevicemotion" in t, this.threshold = 10, this.lastTime = new Date, this.lastX = null, this.lastY = null, this.lastZ = null, "function" == typeof e.CustomEvent) this.event = new e.CustomEvent("shake", {
			bubbles: !0,
			cancelable: !0
		});
		else {
			if ("function" != typeof e.createEvent) return !1;
			this.event = e.createEvent("Event"), this.event.initEvent("shake", !0, !0)
		}
	}
	s.prototype.reset = function() {
		this.lastTime = new Date, this.lastX = null, this.lastY = null, this.lastZ = null
	}, s.prototype.start = function() {
		this.reset(), this.hasDeviceMotion && t.addEventListener("devicemotion", this, !1)
	}, s.prototype.stop = function() {
		this.hasDeviceMotion && t.removeEventListener("devicemotion", this, !1), this.reset()
	}, s.prototype.devicemotion = function(e) {
		var s, i, n = e.accelerationIncludingGravity,
			h = 0,
			o = 0,
			a = 0;
		return null === this.lastX && null === this.lastY && null === this.lastZ ? (this.lastX = n.x, this.lastY = n.y, void(this.lastZ = n.z)) : (h = Math.abs(this.lastX - n.x), o = Math.abs(this.lastY - n.y), a = Math.abs(this.lastZ - n.z), (h > this.threshold && o > this.threshold || h > this.threshold && a > this.threshold || o > this.threshold && a > this.threshold) && (s = new Date, i = s.getTime() - this.lastTime.getTime(), i > 100 && (t.dispatchEvent(this.event), this.lastTime = new Date)), this.lastX = n.x, this.lastY = n.y, void(this.lastZ = n.z))
	}, s.prototype.handleEvent = function(t) {
		return "function" == typeof this[t.type] ? this[t.type](t) : void 0
	};
	var i = new s;
	i && i.start()
}(window, document);