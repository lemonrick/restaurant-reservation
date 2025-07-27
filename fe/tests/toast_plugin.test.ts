import { describe, it, expect, vi } from 'vitest';
import ToastPlugin, { toast } from '@/plugins/toast';

describe('toast plugin', () => {
  it('exports base toast function', () => {
    expect(toast).toBeTypeOf('function');
  });

  it('calls app.use with Toast and options on install', () => {
    const app = { use: vi.fn() };
    ToastPlugin.install(app as never);
    expect(app.use).toHaveBeenCalledWith(
      expect.objectContaining({ install: expect.any(Function) }),
      expect.objectContaining({
        autoClose: 8000,
        position: 'bottom-right',
      })
    );
  });
});
