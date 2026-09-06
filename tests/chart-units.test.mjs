import assert from 'node:assert/strict';
import {readFile} from 'node:fs/promises';
import test from 'node:test';

globalThis.document = {body: {dataset: {}}, documentElement: {lang: 'de'}};
const source = await readFile(new URL('../assets/chart-recipes.js', import.meta.url), 'utf8');
const {temperatureHumidity, dailyRain, rainAccumulation, monthlyComparison} = await import('data:text/javascript;base64,' + Buffer.from(source).toString('base64'));

test('charts bind converted values, axes and tooltips to feed metadata', () => {
    const temperature = {unit: 'degree_F', unitLabel: '°F', decimals: 1, points: [{end: 1, value: 32}, {end: 2, value: null}]};
    const humidity = {unitLabel: '%', decimals: 0, points: []};
    const option = temperatureHumidity({temperature24h: temperature, humidity24h: humidity}, 'UTC');
    assert.equal(option.yAxis[0].name, '°F');
    assert.deepEqual(option.dataset[0].source, [[1000, 32], [2000, null]]);
    assert.equal(option.series[0].tooltip.valueFormatter(32), '32,0 °F');
    assert.equal(option.series[0].tooltip.valueFormatter(null), '—');
    const rain = {unitLabel: 'in', decimals: 2, points: [{start: 1, end: 2, value: 0.01, coverage: 1}]};
    for (const chart of [dailyRain(rain, 'UTC'), rainAccumulation(rain, 'UTC'), monthlyComparison({periods: rain}, 'UTC')]) {
        assert.equal((Array.isArray(chart.yAxis) ? chart.yAxis[0] : chart.yAxis).name, 'in');
        assert.equal(chart.tooltip.valueFormatter(0.01), '0,01 in');
    }
});
