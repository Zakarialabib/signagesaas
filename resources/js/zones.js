window.zoneEditor = function({
    zones,
    width,
    height,
    isFullscreen,
    allContent
}) {
    return {
        zones,
        width,
        height,
        isFullscreen,
        allContent,
        dragging: null,
        resizing: null,
        dragStart: {},
        resizeStart: {},
        showContentModal: false,
        selectedZoneId: null,
        startDrag(e, zoneId) {
            this.dragging = zoneId;
            const zone = this.zones[zoneId];
            this.dragStart = {
                x: e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX,
                y: e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY,
                zoneX: zone.x,
                zoneY: zone.y,
            };
            window.addEventListener('mousemove', this.onDrag);
            window.addEventListener('mouseup', this.stopDrag);
            window.addEventListener('touchmove', this.onDrag, { passive: false });
            window.addEventListener('touchend', this.stopDrag);
        },
        onDrag: (e) => {
            if (this.dragging === null) return;
            e.preventDefault();
            const zone = this.zones[this.dragging];
            const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;
            const dx = ((clientX - this.dragStart.x) / this.$root.offsetWidth) * 100;
            const dy = ((clientY - this.dragStart.y) / this.$root.offsetHeight) * 100;
            zone.x = Math.max(0, Math.min(100 - zone.width, this.dragStart.zoneX + dx));
            zone.y = Math.max(0, Math.min(100 - zone.height, this.dragStart.zoneY + dy));
        },
        stopDrag: () => {
            this.dragging = null;
            window.removeEventListener('mousemove', this.onDrag);
            window.removeEventListener('mouseup', this.stopDrag);
            window.removeEventListener('touchmove', this.onDrag);
            window.removeEventListener('touchend', this.stopDrag);
        },
        startResize(e, zoneId) {
            this.resizing = zoneId;
            const zone = this.zones[zoneId];
            this.resizeStart = {
                x: e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX,
                y: e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY,
                width: zone.width,
                height: zone.height,
            };
            window.addEventListener('mousemove', this.onResize);
            window.addEventListener('mouseup', this.stopResize);
            window.addEventListener('touchmove', this.onResize, { passive: false });
            window.addEventListener('touchend', this.stopResize);
        },
        onResize: (e) => {
            if (this.resizing === null) return;
            e.preventDefault();
            const zone = this.zones[this.resizing];
            const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;
            const dw = ((clientX - this.resizeStart.x) / this.$root.offsetWidth) * 100;
            const dh = ((clientY - this.resizeStart.y) / this.$root.offsetHeight) * 100;
            zone.width = Math.max(5, Math.min(100 - zone.x, this.resizeStart.width + dw));
            zone.height = Math.max(5, Math.min(100 - zone.y, this.resizeStart.height + dh));
        },
        stopResize: () => {
            this.resizing = null;
            window.removeEventListener('mousemove', this.onResize);
            window.removeEventListener('mouseup', this.stopResize);
            window.removeEventListener('touchmove', this.onResize);
            window.removeEventListener('touchend', this.stopResize);
        },
        openContentModal(zoneId) {
            this.selectedZoneId = zoneId;
            this.showContentModal = true;
        },
        closeContentModal() {
            this.showContentModal = false;
            this.selectedZoneId = null;
        },
        assignContentToZone(zoneId, content) {
            this.zones[zoneId].content = content;
            this.closeContentModal();
        },
        saveZones() {
            const zonesToSave = {};
            for (const [zoneId, zone] of Object.entries(this.zones)) {
                zonesToSave[zoneId] = {
                    ...zone,
                    content_id: zone.content?.id ?? null,
                };
            }
            this.$wire.saveZones(zonesToSave);
        }
    }
}