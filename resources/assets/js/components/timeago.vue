<template>
    <span v-text="timeago"></span>
</template>

<script>
    const MINUTE = 60;
    const HOUR = MINUTE * 60;
    const DAY = HOUR * 24;
    const WEEK = DAY * 7;
    const MONTH = DAY * 30;
    const YEAR = DAY * 365;

    export default  {
        props: {
            since: {
                required: true,
                coerce(val) {
                    return new Date(val).getTime()
                }
            },
            maxTime: Number,
            autoUpdate: Number,
            format: Function
        },
        data() {
            return {
                now: new Date().getTime()
            }
        },
        computed: {
            currentLocale() {
                return [
                    "Только что",
                    ["%s секунду назад", "%s секунды назад", "%s секунд назад"],
                    ["%s минуту назад", "%s минуты назад", "%s минут назад"],
                    ["%s час назад", "%s часа надад", "%s часов надад"],
                    ["%s день назад", "%s дня назад", "%s дней назад"],
                    ["%s неделю назад", "%s недели назад", "%s недель назад"],
                    ["%s месяц назад", "%s месяца назад", "%s месяцев назад"],
                    ["%s год назад", "%s года назад", "%s лет назад"]
                ];
            },
            timeago() {
                const seconds = (this.now / 1000) - (this.since / 1000);

                if (this.maxTime && seconds > this.maxTime) {
                    clearInterval(this.interval);
                    return this.format ? this.format(this.since) : this.formatTime(this.since)
                }

                const ret
                        = seconds <= 5
                        ? this.pluralOrSingular('just now', this.currentLocale[0])
                        : seconds < MINUTE
                        ? this.pluralOrSingular(seconds, this.currentLocale[1])
                        : seconds < HOUR
                        ? this.pluralOrSingular(seconds / MINUTE, this.currentLocale[2])
                        : seconds < DAY
                        ? this.pluralOrSingular(seconds / HOUR, this.currentLocale[3])
                        : seconds < WEEK
                        ? this.pluralOrSingular(seconds / DAY, this.currentLocale[4])
                        : seconds < MONTH
                        ? this.pluralOrSingular(seconds / WEEK, this.currentLocale[5])
                        : seconds < YEAR
                        ? this.pluralOrSingular(seconds / MONTH, this.currentLocale[6])
                        : this.pluralOrSingular(seconds / YEAR, this.currentLocale[7]);

                return ret
            }
        },
        ready() {
            if (this.autoUpdate) {
                this.update()
            }
        },
        methods: {
            update() {
                const period = this.autoUpdate * 1000;
                this.interval = setInterval(() => {
                    this.now = new Date().getTime()
                }, period)
            },

            formatTime(time) {
                const d = new Date(time);
                return d.toLocaleString()
            },

            pluralOrSingular(data, locale) {
                if (data === 'just now') {
                    return locale
                }
                const cnt = Math.round(data);
                if (Array.isArray(locale)) {

                    if(cnt % 10 == 1 && cnt % 100 != 11) return locale[0].replace(/%s/, cnt);
                    if(cnt % 10 >= 2 && cnt % 10 <= 4 && ( cnt % 100 == 20)) return locale[1].replace(/%s/, cnt);
                    return locale[2].replace(/%s/, cnt)
                }
                return locale.replace(/%s/, cnt)
            }
        }
    }
</script>