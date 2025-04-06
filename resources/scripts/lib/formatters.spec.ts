import { bytesToString, ip, mbToBytes } from '@/lib/formatters';

describe('@/lib/formatters.ts', function () {
    describe('mbToBytes()', function () {
        it('должен конвертировать из МБ в Байты', function () {
            expect(mbToBytes(1)).toBe(1_048_576);
            expect(mbToBytes(0)).toBe(0);
            expect(mbToBytes(0.1)).toBe(104_857);
            expect(mbToBytes(0.001)).toBe(1_048);
            expect(mbToBytes(1024)).toBe(1_073_741_824);
        });
    });

    describe('bytesToString()', function () {
        it.each([
            [0, '0 Байт'],
            [0.5, '0 Байт'],
            [0.9, '0 Байт'],
            [100, '100 Байт'],
            [100.25, '100.25 Байт'],
            [100.998, '101 Байт'],
            [512, '512 Байт'],
            [1000, '1000 Байт'],
            [1024, '1 КБайт'],
            [5068, '4.95 КБайт'],
            [10_000, '9.77 КБайт'],
            [10_240, '10 КБайт'],
            [11_864, '11.59 КБайт'],
            [1_000_000, '976.56 КБайт'],
            [1_024_000, '1000 КБайт'],
            [1_025_000, '1000.98 КБайт'],
            [1_048_576, '1 МБайт'],
            [1_356_000, '1.29 МБайт'],
            [1_000_000_000, '953.67 МБайт'],
            [1_070_000_100, '1020.43 МБайт'],
            [1_073_741_824, '1 ГБайт'],
            [1_678_342_000, '1.56 ГБайт'],
            [1_000_000_000_000, '931.32 ГБайт'],
            [1_099_511_627_776, '1 ТБайт'],
        ])('should format %d bytes as "%s"', function (input, output) {
            expect(bytesToString(input)).toBe(output);
        });
    });

    describe('ip()', function () {
        it('should format an IPv4 address', function () {
            expect(ip('127.0.0.1')).toBe('127.0.0.1');
        });

        it('should format an IPv6 address', function () {
            expect(ip(':::1')).toBe('[:::1]');
            expect(ip('2001:db8::')).toBe('[2001:db8::]');
        });

        it('should handle random inputs', function () {
            expect(ip('1')).toBe('1');
            expect(ip('foobar')).toBe('foobar');
            expect(ip('127.0.0.1:25565')).toBe('[127.0.0.1:25565]');
        });
    });
});
