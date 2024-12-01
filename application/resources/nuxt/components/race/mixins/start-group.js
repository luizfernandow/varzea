export default {
    methods: {
        sortPositions() {
            const sortable = this.racersPositions
            const self = this
            sortable.sort((a, b) => {
                const timerA = self.racersTime[a.groupNumber]
                const timerB = self.racersTime[b.groupNumber]
                if (timerA.lap === timerB.lap) {
                    return timerA.totalSeconds - timerB.totalSeconds
                }
                return timerB.lap - timerA.lap
            })
            this.racersPositions = sortable
        },
        init() {
            this.racersPositions = []
            for (const group of Object.values(this.racers)) {
                for (const racer of group) {
                    this.racersByNumber[`${racer.group}${racer.number}`] = racer
                    this.racersByRfid[`${racer.rfid_code}`] = racer
                }
                const groupNumber = this.getGroupIdKey(group[0].group)
                this.racersTime[groupNumber] = {
                    lap: 0,
                    laps: [],
                    lapsNumber: [],
                    totalSeconds: 0,
                }
                this.lapText[groupNumber] = []
                this.racersPositions.push({
                    groupNumber,
                    group,
                })
            }
        },
        getGroupId(items) {
            return this.getGroupIdKey(items[0].group)
        },
        getGroupIdKey(groupId) {
            return `group_${groupId}`
        },
    },
}
